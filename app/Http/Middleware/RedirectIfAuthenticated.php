<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // Cek role dan redirect sesuai dashboard
            $role = Auth::user()->role;
            if ($role === 'gurubk') {
                return redirect()->route('dashboard.index');
            } elseif ($role === 'wakel') {
                return redirect()->route('dashboard.wakel');
            } elseif ($role === 'kepsek') {
                return redirect()->route('dashboard.kepsek');
            }
        }

        return $next($request);
    }
}
