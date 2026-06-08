<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        return view('seller.settings', ['user' => $request->user()]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'store_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:1000',
        ]);

        $request->user()->update($request->only(['name', 'store_name', 'phone', 'address']));

        return back()->with('success', 'Profil toko berhasil diperbarui.');
    }

    public function toggleClosed(Request $request)
    {
        $user = $request->user();
        $user->update(['is_closed' => !$user->is_closed]);

        $status = $user->is_closed ? 'ditutup' : 'dibuka';
        return back()->with('success', "Toko berhasil $status.");
    }
}
