<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SellerApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class SellerApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $application = auth()->user()->sellerApplication;
        return view('user.seller-application.create', compact('application'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // Check if user already has a pending or approved application
        $existing = SellerApplication::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existing) {
            return redirect()->route('seller-application.create')
                ->with('error', 'Anda sudah memiliki pengajuan penjual yang sedang diproses.');
        }

        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'contact' => 'required|string|max:20',
            'product_type' => 'required|string|max:255',
        ]);

        SellerApplication::create([
            'user_id' => $user->id,
            ...$validated,
        ]);

        return redirect()->route('seller-application.create')
            ->with('success', 'Pengajuan penjual Anda telah dikirimkan. Silakan tunggu persetujuan admin.');
    }
}
