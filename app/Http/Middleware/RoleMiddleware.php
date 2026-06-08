<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage: middleware('role:admin') or middleware('role:admin|seller')
     */
    public function handle(Request $request, Closure $next, $roles = null)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (is_null($roles) || $roles === '') {
            return $next($request);
        }

        $allowed = preg_split('/[\|,]/', $roles);

        if (in_array($user->role, $allowed, true)) {
            return $next($request);
        }

        abort(403);
    }
}
