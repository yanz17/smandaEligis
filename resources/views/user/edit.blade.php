@extends('layouts.dashboard')

@section('title', 'Edit User')

@section('content')
<h2 class="text-3xl text-center text-yellow-500 font-bold mb-4">Edit User</h2>

<form action="{{ route('user.update', $user->id) }}" method="POST" class="space-y-4">
    @csrf
    @method('PUT')

    <div>
        <label class="block mb-1">Username</label>
        <input type="text" name="username" class="input input-bordered w-full text-black" value="{{ old('username', $user->username) }}" required>
        @error('username') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block mb-1">Password</label>
        <input type="text" name="password" class="input input-bordered w-full text-black" value="{{ old('password', $user->password) }}" required>
        @error('password') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block mb-1">Role</label>
        <select name="role" class="select select-bordered w-full text-black" required>
            <option value="">Pilih Role</option>
            <option value="gurubk">Guru BK</option>
            <option value="wakel">Wali Kelas</option>
            <option value="kepsek">Kepala Sekolah</option>
        </select>
        @error('role') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
    </div>

    <div class="flex flex-col items-center gap-3 mt-8">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="/dashboard/gurubk?tab=user" class="link link-hover link-error">Kembali</a>
    </div>
</form>
@endsection
