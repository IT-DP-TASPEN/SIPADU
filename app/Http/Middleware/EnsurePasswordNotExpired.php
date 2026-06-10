<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordNotExpired
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        // Skip if already on force change password page or logging out
        if ($request->routeIs('force-change-password', 'force-change-password.store', 'logout')) {
            return $next($request);
        }

        // Must change password flag
        if ($user->must_change_password) {
            return redirect()->route('force-change-password')
                ->with('warning', 'Anda wajib mengganti password sebelum dapat mengakses sistem.');
        }

        // Password expired
        if ($user->isPasswordExpired()) {
            return redirect()->route('force-change-password')
                ->with('warning', 'Password Anda telah kedaluwarsa. Silakan ganti password Anda.');
        }

        return $next($request);
    }
}
