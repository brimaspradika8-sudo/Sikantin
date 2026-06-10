<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->where('role', '=', 'user');

        if ($request->filled('search')) {
            $query->where(fn ($q) =>
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
            );
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        abort_unless($user->role === 'user', 403);

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        abort_unless($user->role === 'user', 403);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:25',
            'status' => 'required|in:active,inactive,pending,rejected',
        ]);

        $user->update($request->only(['name', 'email', 'phone', 'status']));

        AuditLog::create([
            'actor_id' => Auth::id(),
            'subject_id' => $user->id,
            'action' => 'update_user',
            'description' => 'Mengubah profil pengguna ' . $user->name,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user)
    {
        abort_unless($user->role === 'user', 403);

        AuditLog::create([
            'actor_id' => Auth::id(),
            'subject_id' => $user->id,
            'action' => 'delete_user',
            'description' => 'Menghapus pengguna ' . $user->email,
            'ip_address' => $request->ip(),
        ]);

        User::destroy($user->id);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function toggleStatus(Request $request, User $user)
    {
        abort_unless($user->role === 'user', 403);

        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);

        AuditLog::create([
            'actor_id' => Auth::id(),
            'subject_id' => $user->id,
            'action' => 'toggle_user_status',
            'description' => 'Mengubah status pengguna ' . $user->name . ' menjadi ' . $newStatus,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Status pengguna diperbarui menjadi ' . $newStatus . '.');
    }
}
