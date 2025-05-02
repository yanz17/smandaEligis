<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        $users = $query->paginate(10);

        return view('dashboard.index', compact('users'));
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'role' => 'required|string|max:255',
        ]);

        User::create($request->all());

        return redirect()->route('dashboard.index', ['tab' => 'user'])->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        return view('user.edit', compact( 'users'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'role' => 'required|string|max:255',
        ]);

        $user->update($request->all());

        return redirect()->route('dashboard.index', ['tab' => 'user'])->with('success', 'User berhasil diupdate!');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('dashboard.index', ['tab' => 'user'])->with('success', 'User berhasil dihapus!');
    }
}
