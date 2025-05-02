@extends('layouts.dashboard')

@section('title', 'Tambah Siswa')

@section('content')
<h2 class="text-xl font-semibold mb-4">Tambah Siswa</h2>

<form action="{{ route('siswa.store') }}" method="POST" class="space-y-4">
    @csrf
    <div>
        <label class="block mb-1">Nama</label>
        <input type="text" name="nama" class="input input-bordered w-full" value="{{ old('nama') }}" required>
        @error('nama') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block mb-1">Kelas</label>
        <select name="kelas_id" class="select select-bordered w-full" required>
            <option value="">Pilih Kelas</option>
            @foreach($kelas as $k)
                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
            @endforeach
        </select>
        @error('kelas_id') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
@endsection
