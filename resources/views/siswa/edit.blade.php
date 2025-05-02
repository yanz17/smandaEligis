@extends('layouts.dashboard')

@section('title', 'Edit Siswa')

@section('content')
<h2 class="text-xl font-semibold mb-4">Edit Siswa</h2>

<form action="{{ route('siswa.update', $siswa->id) }}" method="POST" class="space-y-4">
    @csrf
    @method('PUT')

    <div>
        <label class="block mb-1">Nama</label>
        <input type="text" name="nama" class="input input-bordered w-full" value="{{ old('nama', $siswa->nama) }}" required>
        @error('nama') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block mb-1">Kelas</label>
        <select name="kelas_id" class="select select-bordered w-full" required>
            <option value="">Pilih Kelas</option>
            @foreach($kelas as $k)
                <option value="{{ $k->id }}" {{ $siswa->kelas_id == $k->id ? 'selected' : '' }}>
                    {{ $k->nama_kelas }}
                </option>
            @endforeach
        </select>
        @error('kelas_id') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
</form>
@endsection
