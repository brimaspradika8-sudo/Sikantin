<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $emailRole = User::inferRoleFromEmail($validated['email']);
        $role = $emailRole !== 'user' ? $emailRole : $request->role;
        $status = $role === 'seller' && $emailRole === 'user' ? 'pending' : 'active';

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $role,
            'store_name' => $validated['store_name'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'status' => $status,
        ]);

        if (method_exists($user, 'assignRole')) {
            if (class_exists(\Spatie\Permission\Models\Role::class)) {
                \Spatie\Permission\Models\Role::findOrCreate($role, 'web');
            }
            $user->assignRole($role);
        }

        event(new Registered($user));

        if ($request->role === 'seller') {
            return redirect(route('login', absolute: false))->with('status', 'Pendaftaran berhasil. Akun penjual Anda sedang menunggu persetujuan Admin.');
        }

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
