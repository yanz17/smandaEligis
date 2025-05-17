@extends('layouts.dashboard')

@section('title', 'Edit Siswa')

@section('content')
<h2 class="text-xl font-semibold mb-4">Edit Siswa</h2>

<form action="{{ route('changeRequests.storeRequest', ['model' => 'siswa', 'id' => $siswa->id]) }}" method="POST" class="space-y-4">
    @csrf

    <div>
        <label class="block mb-1">NIS</label>
        <input type="text" name="id" class="input input-bordered w-full text-black" value="{{ old('id', $siswa->id) }}" required>
        @error('id') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block mb-1">Nama</label>
        <input type="text" name="nama" class="input input-bordered w-full text-black" value="{{ old('nama', $siswa->nama) }}" required>
        @error('nama') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block mb-1">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" class="input input-bordered w-full text-black" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir) }}" required>
        @error('tanggal_lahir') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block mb-1">Kelas</label>
        <select name="kelas_id" class="select select-bordered w-full text-black" required>
            <option value="">Pilih Kelas</option>
            @foreach($kelas as $k)
                <option value="{{ $k->id }}" {{ $siswa->kelas_id == $k->id ? 'selected' : '' }} class="text-black">
                    {{ $k->nama_kelas }}
                </option>
            @endforeach
        </select>
        @error('kelas_id') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
    </div>

    <button type="submit" class="btn btn-warning">Ajukan Perubahan</button>
</form>
@endsection
