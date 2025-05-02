@extends('layouts.dashboard')

@section('title', 'Tambah Nilai')

@section('content')
<h2 class="text-xl font-semibold mb-4">Tambah Nilai</h2>

<form action="{{ route('nilai.store') }}" method="POST" class="space-y-4">
    @csrf

    <div>
        <label class="block mb-1">Siswa</label>
        <select id="siswa-select" name="siswa_id" class="select select-bordered w-full" required>
            <option value="">Pilih Siswa</option>
            @foreach($siswas as $s)
                <option value="{{ $s->id }}">{{ $s->nama }} - {{ $s->kelas->nama_kelas ?? '-' }}</option>
            @endforeach
        </select>
    </div>

    @for ($i = 1; $i <= 5; $i++)
        <div>
            <label class="block mb-1">Semester {{ $i }}</label>
            <input type="number" step="0.01" name="sem_{{ $i }}" class="input input-bordered w-full">
        </div>
    @endfor

    <div>
        <label class="block mb-1">Prestasi</label>
        <input type="text" name="prestasi" class="input input-bordered w-full">
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        new TomSelect("#siswa-select", {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            },
            placeholder: "Cari nama siswa...",
        });
    });
</script>
@endsection
