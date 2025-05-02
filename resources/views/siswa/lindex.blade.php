@extends('layouts.dashboard')

@section('title', 'Data Siswa')

@section('content')
@if (session('success'))
    <div class="alert alert-success mb-4">{{ session('success') }}</div>
@endif

<div class="flex justify-between items-center mb-4">
    <h2 class="text-xl font-semibold">List Siswa</h2>
    <a href="{{ route('siswa.create') }}" class="btn btn-primary">Tambah Siswa</a>
</div>

<form method="GET" action="{{ route('dashboard.siswa') }}" class="flex gap-2 mb-4">
    <input type="text" name="search" placeholder="Cari nama..." class="input input-bordered" value="{{ request('search') }}">
    <select name="kelas_id" class="select select-bordered">
        <option value="">Semua Kelas</option>
        @foreach($kelas as $k)
            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                {{ $k->nama }}
            </option>
        @endforeach
    </select>
    <button class="btn btn-secondary">Filter</button>
</form>

<div class="overflow-x-auto">
    <table class="table w-full">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($siswas as $siswa)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $siswa->nama }}</td>
                <td>{{ $siswa->kelas->nama }}</td>
                <td class="flex gap-2">
                    <a href="{{ route('siswa.edit', $siswa->id) }}" class="btn btn-xs btn-warning">Edit</a>
                    <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST" onsubmit="return confirm('Yakin mau hapus?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-xs btn-error">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center">Data kosong</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $siswas->withQueryString()->links() }}
</div>
@endsection
