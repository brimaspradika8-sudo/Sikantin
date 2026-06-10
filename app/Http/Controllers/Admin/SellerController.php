<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SellerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'seller');

        if ($request->filled('search')) {
            $query->where(fn ($q) =>
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('store_name', 'like', '%' . $request->search . '%')
            );
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sellers = $query->latest()->paginate(15)->withQueryString();

        return view('admin.sellers.index', compact('sellers'));
    }

    public function approve(User $seller)
    {
        abort_unless($seller->role === 'seller', 403);

        $needsPassword = empty($seller->password);

        if ($needsPassword) {
            $password = Str::random(12);
            $seller->update([
                'status' => 'active',
                'password' => Hash::make($password),
            ]);

            Mail::raw("Akun penjual Anda telah disetujui. Silakan masuk ke dashboard penjual menggunakan email: {$seller->email} dan password: {$password}", function ($message) use ($seller) {
                $message->to($seller->email)
                    ->subject('Akun Penjual Disetujui');
            });
        } else {
            $seller->update(['status' => 'active']);

            Mail::raw("Akun penjual Anda telah disetujui dan diaktifkan. Silakan login kembali menggunakan email: {$seller->email}", function ($message) use ($seller) {
                $message->to($seller->email)
                    ->subject('Akun Penjual Disetujui');
            });
        }

        AuditLog::create([
            'actor_id' => auth()->id(),
            'subject_id' => $seller->id,
            'action' => 'approve_seller',
            'description' => 'Menyetujui akun penjual ' . $seller->name,
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Akun penjual disetujui dan email kredensial telah dikirim.');
    }

    public function reject(User $seller)
    {
        abort_unless($seller->role === 'seller', 403);

        $seller->update(['status' => 'rejected']);

        Mail::raw("Akun penjual Anda ditolak. Silakan hubungi administrator untuk informasi lebih lanjut.", function ($message) use ($seller) {
            $message->to($seller->email)
                ->subject('Akun Penjual Ditolak');
        });

        AuditLog::create([
            'actor_id' => auth()->id(),
            'subject_id' => $seller->id,
            'action' => 'reject_seller',
            'description' => 'Menolak akun penjual ' . $seller->name,
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Akun penjual ditolak dan email notifikasi telah dikirim.');
    }

    public function destroy(User $seller)
    {
        abort_unless($seller->role === 'seller', 403);

        AuditLog::create([
            'actor_id' => auth()->id(),
            'subject_id' => $seller->id,
            'action' => 'delete_seller',
            'description' => 'Menghapus akun penjual ' . $seller->name,
            'ip_address' => request()->ip(),
        ]);

        $seller->products()->delete();
        $seller->delete();

        return back()->with('success', 'Akun penjual berhasil dihapus.');
    }
}
