@extends('layouts.dashboard')

@section('title', 'Tambah Siswa')

@section('content')
<h2 class="text-2xl font-bold mb-4 text-yellow-500 text-center">Tambah Siswa</h2>

@if (session('error'))
    <div class="alert alert-warning shadow-lg mb-4">
        <div>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-error shadow-lg mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        <div>
            <h3 class="font-bold">Terjadi kesalahan:</h3>
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<form action="{{ route('siswa.store') }}" method="POST" class="space-y-4">
    @csrf
    <div>
        <label class="block mb-1">NIS</label>
        <input type="text" name="id" class="input input-bordered w-full text-white"
            minlength="10" maxlength="10" required
            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
            placeholder="Masukkan NIS 10 digit">
        @error('id') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block mb-1">Nama</label>
        <input 
            type="text" name="nama" 
            class="input input-bordered w-full text-white" 
            value="{{ old('nama') }}" 
            placeholder="Masukkan nama lengkap"
            required>
        @error('nama') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block mb-1">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" class="input input-bordered w-full text-white" value="{{ old('tanggal_lahir') }}" required>
        @error('tanggal_lahir') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block mb-1">Kelas</label>
        @if($kelas->count() === 1)
            <input type="hidden" name="kelas_id" value="{{ $kelas->first()->id }}">
            <input type="text" value="{{ $kelas->first()->nama_kelas }}" class="input input-bordered w-full text-white bg-gray-100" disabled>
        @else
            <select name="kelas_id" class="select select-bordered w-full text-white" required>
                <option value="">Pilih Kelas</option>
                @foreach($kelas as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                @endforeach
            </select>
        @endif
        @error('kelas_id') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
    </div>

    <div class="w-full flex flex-col gap-5 items-center">
        <button type="submit" class="btn btn-primary w-4xs">Simpan</button>
        <a href="/dashboard/gurubk?tab=siswa" class="link link-error link-hover text-center my-auto">
            Kembali
        </a>
    </div>
</form>
@endsection
