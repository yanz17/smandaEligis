<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login')->with('error', 'Kamu harus login dulu.');
        }

        // Jika role user tidak ada di daftar role yang diperbolehkan
        if (!in_array($user->role, $roles)) {
            return redirect('/')->with('error', 'Kamu tidak punya akses.');
        }

        return $next($request);
    }
}
