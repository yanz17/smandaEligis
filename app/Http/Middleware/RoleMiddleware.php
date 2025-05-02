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
        // Ambil user yang login
        $user = Auth::user();

        // Kalau belum login atau user tidak ditemukan
        if (!$user) {
            return redirect('/login')->with('error', 'Kamu harus login dulu.');
        }

        // Ambil role yang dibutuhkan dari parameter pertama
        $role = $roles[0] ?? null;

        // Kalau role user tidak sama, tendang
        if ($role && $user->role !== $role) {
            return redirect('/login')->with('error', 'Kamu tidak punya akses.');
        }

        // Kalau semua aman, lanjut
        return $next($request);
    }
}
