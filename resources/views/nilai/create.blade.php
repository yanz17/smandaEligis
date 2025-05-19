@extends('layouts.dashboard')

@section('title', 'Tambah Nilai')

@section('content')
<h2 class="text-2xl font-bold text-yellow-500 text-center mb-4">Tambah Nilai</h2>

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

<form action="{{ route('nilai.store') }}" method="POST" class="space-y-4">
    @csrf

    <div>
        <label class="block mb-1">Siswa</label>
        <select id="siswa-select" name="siswa_id" class="select select-bordered w-full" required>
            <option value="">Pilih Siswa</option>
            @foreach($siswas as $s)
                <option value="{{ $s->id }}" {{ old('siswa_id') == $s->id ? 'selected' : '' }}>
                    {{ $s->nama }} - {{ $s->kelas->nama_kelas ?? '-' }}
                </option>
            @endforeach
        </select>
        @error('siswa_id')
            <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
    </div>

    <div class="flex flex-row gap-3">  
        @for ($i = 1; $i <= 5; $i++)
            <div>
                <label class="block mb-1">Semester {{ $i }}</label>
                <input type="number" step="0.01" name="sem_{{ $i }}" required class="input input-bordered w-4xs text-black" value="{{ old('sem_'.$i) }}">
                @error('sem_'.$i)
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>
        @endfor
    </div>

    <div class="flex flex-row gap-3 mb-0">
        <div>
            <label class="block mb-1">Juara</label>
            <select id="juara" class="select select-bordered w-4xs text-black">
                <option value="">Pilih Juara</option>
                <option value="1" {{ old('juara') == '1' ? 'selected' : '' }}>Juara 1</option>
                <option value="2" {{ old('juara') == '2' ? 'selected' : '' }}>Juara 2</option>
                <option value="3" {{ old('juara') == '3' ? 'selected' : '' }}>Juara 3</option>
            </select>
        </div>
        
        <div>
            <label class="block mb-1">Tingkat</label>
            <select id="tingkat" class="select select-bordered w-4xs text-black">
                <option value="">Pilih Tingkat</option>
                <option value="kecamatan" {{ old('tingkat') == 'kecamatan' ? 'selected' : '' }}>Kecamatan</option>
                <option value="kabupaten" {{ old('tingkat') == 'kabupaten' ? 'selected' : '' }}>Kabupaten</option>
                <option value="provinsi" {{ old('tingkat') == 'provinsi' ? 'selected' : '' }}>Provinsi</option>
                <option value="nasional" {{ old('tingkat') == 'nasional' ? 'selected' : '' }}>Nasional</option>
                <option value="internasional" {{ old('tingkat') == 'internasional' ? 'selected' : '' }}>Internasional</option>
            </select>
        </div>
        
        <div>
            <label class="block mb-1">Nilai Prestasi</label>
            <input type="number" step="0.1" name="prestasi" id="prestasi" class="input input-bordered w-4xs text-black" readonly required value="{{ old('prestasi') }}">
            @error('prestasi')
                <span class="text-sm text-red-500">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <p class="text-xs text-warning mt-1">*kosongkan jika tidak memiliki riwayat prestasi</p>


    <div class="flex flex-col gap-3 items-center mt-8">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="/dashboard/gurubk?tab=nilai" class="link link-hover link-error">Kembali</a>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (!document.getElementById('siswa-select').tomselect) {
            new TomSelect("#siswa-select", {
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

            const nilai = nilaiMap[tingkat]?.[juara] ?? 0;
            prestasiInput.value = nilai;
        }

        juaraSelect.addEventListener('change', updatePrestasi);
        tingkatSelect.addEventListener('change', updatePrestasi);

        // Jalankan saat load awal (kalau user kembali karena error)
        updatePrestasi();
    });

    // Validasi frontend sebelum submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const semFields = [1, 2, 3, 4, 5].map(i => document.querySelector(`input[name="sem_${i}"]`));
        const prestasi = document.getElementById('prestasi');
        const siswaSelect = document.getElementById('siswa-select');

        let isValid = true;

        semFields.forEach(input => input.classList.remove('border-red-500'));
        prestasi.classList.remove('border-red-500');
        siswaSelect.classList.remove('border-red-500');

        if (!siswaSelect.value.trim()) {
            siswaSelect.classList.add('border-red-500');
            isValid = false;
        }

        semFields.forEach(input => {
            if (!input.value.trim()) {
                input.classList.add('border-red-500');
                isValid = false;
            }
        });

        if (!prestasi.value.trim()) {
            prestasi.classList.add('border-red-500');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            alert('Pastikan semua nilai semester dan prestasi sudah diisi!');
        }
    });
</script>
@endsection
