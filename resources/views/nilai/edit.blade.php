@extends('layouts.dashboard')

@section('title', 'Edit Nilai')

@section('content')
<h2 class="text-3xl text-yellow-500 text-center font-bold mb-4">Edit Nilai</h2>

<form action="{{ route('nilai.update', $nilai->id) }}" method="POST" class="space-y-4">
    @csrf
    @method('PUT')

    <div>
        <label class="block mb-1">Siswa</label>
        <select id="siswa-select" name="siswa_id" class="select select-bordered w-full text-white" required>
            <option value="">Pilih Siswa</option>
            @foreach($siswas as $s)
                <option value="{{ $s->id }}" {{ $nilai->siswa_id == $s->id ? 'selected' : '' }}>
                    {{ $s->nama }} - {{ $s->kelas->nama_kelas ?? '-' }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="flex flex-row gap-3">
        @for ($i = 1; $i <= 5; $i++)
            <div>
                <label class="block mb-1">Semester {{ $i }}</label>
                <input type="number" step="0.01" name="sem_{{ $i }}" class="input input-bordered w-4xs text-white"
                    value="{{ old("sem_$i", number_format($nilai["sem_$i"], 2, '.', '')) }}">
            </div>
        @endfor
    </div>
    
    <div class="flex flex-row gap-3">
        <div>
            <label class="block mb-1">Juara</label>
            <select id="juara" class="select select-bordered w-4xs text-white">
                <option value="">Pilih Juara</option>
                @for ($j = 1; $j <= 3; $j++)
                    <option value="{{ $j }}" {{ $nilai->juara == $j ? 'selected' : '' }}>Juara {{ $j }}</option>
                @endfor
            </select>
        </div>
    
        <div>
            <label class="block mb-1">Tingkat Prestasi</label>
            <select id="tingkat" class="select select-bordered w-4xs text-white">
                @php
                    $tingkatan = ['kecamatan', 'kabupaten', 'provinsi', 'nasional', 'internasional'];
                @endphp
                <option value="">Pilih Tingkat</option>
                @foreach($tingkatan as $t)
                    <option value="{{ $t }}" {{ $nilai->tingkat === $t ? 'selected' : '' }}>
                        {{ ucfirst($t) }}
                    </option>
                @endforeach
            </select>
        </div>
    
        <div>
            <label class="block mb-1">Nilai Prestasi</label>
            <input type="text" name="prestasi" id="prestasi" class="input input-bordered w-4xs text-white" 
                value="{{ old('prestasi', $nilai->prestasi) }}" placeholder="{{ $nilai->prestasi }}" readonly>
        </div>
    </div>

    <div class="flex flex-col items-center gap-3 mt-8">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="/dashboard/gurubk?tab=nilai" class="link link-hover link-error">Kembali</a>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // TomSelect protection
        const selectEl = document.getElementById('siswa-select');
        if (!selectEl.tomselect) {
            new TomSelect(selectEl, {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                placeholder: "Cari nama siswa...",
            });
        }

        const juaraSelect = document.getElementById('juara');
        const tingkatSelect = document.getElementById('tingkat');
        const prestasiInput = document.getElementById('prestasi');

        function updatePrestasi() {
            const juara = parseInt(juaraSelect.value);
            const tingkat = tingkatSelect.value;

            const nilaiMap = {
                kecamatan: { 3: 1, 2: 1.5, 1: 2 },
                kabupaten: { 3: 2.5, 2: 3, 1: 3.5 },
                provinsi: { 3: 4, 2: 4.5, 1: 5 },
                nasional: { 3: 5.5, 2: 6, 1: 6.5 },
                internasional: { 3: 7, 2: 7.5, 1: 8 },
            };

            const nilai = nilaiMap[tingkat]?.[juara] ?? '';
            prestasiInput.value = nilai;
        }

        juaraSelect.addEventListener('change', updatePrestasi);
        tingkatSelect.addEventListener('change', updatePrestasi);

        // Trigger update saat halaman dimuat jika nilai sudah terisi
        updatePrestasi();
    });
</script>
@endsection
