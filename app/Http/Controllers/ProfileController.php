<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\SellerRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function sellerRequestForm(Request $request): View
    {
        $user = $request->user();

        abort_unless($user->role === 'user' || ($user->role === 'seller' && $user->status === 'rejected'), 403);

        return view('profile.seller-request', [
            'user' => $user,
        ]);
    }

    public function sellerRequestSubmit(SellerRequest $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->role === 'user' || ($user->role === 'seller' && $user->status === 'rejected'), 403);

        $validated = $request->validated();

        $user->fill(array_merge($validated, [
            'role' => 'seller',
            'status' => 'pending',
        ]))->save();

        return Redirect::route('profile.edit')->with('status', 'seller-requested');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
