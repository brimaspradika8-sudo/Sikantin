<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SellerApplicationApproved;
use App\Models\SellerApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SellerApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()?->role !== 'admin') {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $applications = SellerApplication::with('user')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.seller-applications.index', compact('applications'));
    }

    public function show(SellerApplication $application)
    {
        return view('admin.seller-applications.show', compact('application'));
    }

    public function approve(SellerApplication $application)
    {
        if ($application->status !== 'pending') {
            return back()->with('error', 'Hanya aplikasi pending yang dapat disetujui.');
        }

        $sellerUser = $application->user;
        $sellerUser->update([
            'role' => 'seller',
            'status' => 'active',
            'store_name' => $application->business_name,
            'address' => $application->address,
            'phone' => $application->contact,
        ]);

        $application->update([
            'status' => 'approved',
            'seller_user_id' => $sellerUser->id,
        ]);

        try {
            Mail::to($sellerUser->email)->send(
                new SellerApplicationApproved($application, $sellerUser->email, null)
            );
        } catch (\Exception $e) {
            Log::error('Failed to send seller approval email: ' . $e->getMessage());
        }

        return back()->with('success', 'Aplikasi disetujui. Pengaju dapat login kembali dengan email ini.');
    }

    public function reject(Request $request, SellerApplication $application)
    {
        if ($application->status !== 'pending') {
            return back()->with('error', 'Hanya aplikasi pending yang dapat ditolak.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $application->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        // TODO: Send email to buyer about rejection
        // TODO: Add notification to buyer profile

        return back()->with('success', 'Aplikasi ditolak.');
    }
}
