<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SellerApplicationApproved;
use App\Models\SellerApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SellerApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'admin') {
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

        // Generate seller account
        $buyerEmail = $application->user->email;
        $domain = substr($buyerEmail, strpos($buyerEmail, '@') + 1);
        $sellerEmail = 'seller-' . Str::random(8) . '@' . $domain;
        $sellerPassword = Str::random(12);

        $sellerUser = User::create([
            'name' => $application->business_name,
            'email' => $sellerEmail,
            'password' => Hash::make($sellerPassword),
            'role' => 'seller',
            'phone' => $application->contact,
            'store_name' => $application->business_name,
            'address' => $application->address,
            'status' => 'active',
        ]);

        $application->update([
            'status' => 'approved',
            'seller_user_id' => $sellerUser->id,
        ]);

        // Send email to buyer with seller credentials
        try {
            Mail::to($application->user->email)->send(
                new SellerApplicationApproved($application, $sellerEmail, $sellerPassword)
            );
        } catch (\Exception $e) {
            \Log::error('Failed to send seller approval email: ' . $e->getMessage());
        }

        return back()->with('success', "Aplikasi disetujui. Email penjual: {$sellerEmail}");
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
