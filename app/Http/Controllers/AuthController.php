<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // kalau nanti mau pakai Hash::check

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if ($user && $user->password == $request->password) {
            Auth::login($user);

            return match($user->role) {
                'gurubk' => redirect()->route('dashboard.index'),
                'wakel' => redirect()->route('dashboard.wakel'),
                'kepsek' => redirect()->route('dashboard.kepsek'),
                default => abort(403),
            };
        }

        return back()->withErrors(['login' => 'Username atau password salah.']);
    }

    public function logout(Request $request)
    {
        //Auth::logout(); // kalau udah pake Auth::login, keluarinnya pake Auth::logout
        //return redirect()->route('login');

        Auth::logout(); // Keluarin user
        $request->session()->invalidate(); // Hapus session
        $request->session()->regenerateToken(); // Regenerate CSRF token baru

        return redirect('/'); // Arahkan ke halaman home
    }

    public function dashboardGurubk()
    {
        return view('dashboard.index', ['user' => Auth::user()]);
    }

    public function dashboardWakel()
    {
        return view('dashboard.wakel', ['user' => Auth::user()]);
    }

    public function dashboardKepsek()
    {
        return view('dashboard.kepsek', ['user' => Auth::user()]);
    }
}
