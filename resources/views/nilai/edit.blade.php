@extends('layouts.dashboard')

@section('title', 'Edit Nilai')

@section('content')
<h2 class="text-xl font-semibold mb-4">Edit Nilai</h2>

<form action="{{ route('nilai.update', $nilai->id) }}" method="POST" class="space-y-4">
    @csrf
    @method('PUT')

    <div>
        <label class="block mb-1">Siswa</label>
        <select name="siswa_id" class="select select-bordered w-full" required>
            <option value="">Pilih Siswa</option>
            @foreach($siswas as $s)
                <option value="{{ $s->id }}" {{ $nilai->siswa_id == $s->id ? 'selected' : '' }}>
                    {{ $s->nama }} - {{ $s->kelas->nama_kelas ?? '-' }}
                </option>
            @endforeach
        </select>
    </div>

    @for ($i = 1; $i <= 5; $i++)
        <div>
            <label class="block mb-1">Semester {{ $i }}</label>
            <input type="number" step="0.01" name="sem_{{ $i }}" class="input input-bordered w-full" value="{{ old("sem_$i", $nilai["sem_$i"]) }}">
        </div>
    @endfor

    <div>
        <label class="block mb-1">Prestasi</label>
        <input type="text" name="prestasi" class="input input-bordered w-full" value="{{ old('prestasi', $nilai->prestasi) }}">
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
</form>
@endsection
