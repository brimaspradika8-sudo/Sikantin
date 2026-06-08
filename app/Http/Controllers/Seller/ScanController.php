<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\PickupQrcode;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function index()
    {
        return view('seller.scan');
    }

    public function scan(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $qrcode = PickupQrcode::where('token', $request->token)->with('order')->first();

        if (! $qrcode) {
            return back()->with('error', 'QR tidak valid.');
        }

        if ($qrcode->is_used) {
            return back()->with('warning', 'QR ini sudah digunakan.');
        }

        if ($qrcode->is_expired) {
            return back()->with('warning', 'QR ini sudah kadaluarsa.');
        }

        if (! $qrcode->order || $qrcode->order->seller_id !== auth()->id()) {
            return back()->with('error', 'QR tidak cocok dengan toko Anda.');
        }

        $qrcode->update(['is_used' => true]);
        $qrcode->order->update(['status' => 'completed']);

        return back()->with('success', 'QR berhasil discan dan pesanan ditandai selesai.');
    }
}
