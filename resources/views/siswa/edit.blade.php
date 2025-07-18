@extends('layouts.dashboard')

@section('title', 'Edit Siswa')

@section('content')
<h2 class="text-3xl text-center text-yellow-500 font-bold mb-4">Edit Siswa</h2>

<form action="{{ route('siswa.update', $siswa->id) }}" method="POST" class="space-y-4">
    @csrf
    @method('PUT')

    <div>
        <label class="block mb-1">NIS</label>
        <input type="text" name="id" class="input input-bordered w-full text-white" value="{{ old('id', $siswa->id) }}" required>
        @error('id') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block mb-1">Nama</label>
        <input type="text" name="nama" class="input input-bordered w-full text-white" value="{{ old('nama', $siswa->nama) }}" required>
        @error('nama') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block mb-1">Tanggal Lahir</label>
        <input type="text" name="tanggal_lahir" class="input input-bordered w-full text-white" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir) }}" required>
        @error('tanggal_lahir') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block mb-1">Kelas</label>
            <select name="kelas_id" class="select select-bordered w-full text-white" required>
                <option value="">Pilih Kelas</option>
                @foreach($kelas as $k)
                    <option value="{{ $k->id }}" {{ old('kelas_id', $siswa->kelas_id) == $k->id ? 'selected' : '' }}>
                        {{ $k->nama_kelas }}
                    </option>
                @endforeach
            </select>
        @error('kelas_id') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
    </div>

    <div class="flex flex-col items-center gap-3 mt-8">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="/dashboard/gurubk?tab=siswa" class="link link-hover link-error">Kembali</a>
    </div>
</form>
@endsection
