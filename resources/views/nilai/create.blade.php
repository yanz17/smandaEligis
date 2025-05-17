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
            <input type="number" step="0.01" name="sem_{{ $i }}" class="input input-bordered w-full text-black">
        </div>
    @endfor

    <div>
        <label class="block mb-1">Juara</label>
        <select id="juara" class="select select-bordered w-full text-black">
            <option value="">Pilih Juara</option>
            <option value="1">Juara 1</option>
            <option value="2">Juara 2</option>
            <option value="3">Juara 3</option>
        </select>
    </div>
    
    <div>
        <label class="block mb-1">Tingkat</label>
        <select id="tingkat" class="select select-bordered w-full text-black">
            <option value="">Pilih Tingkat</option>
            <option value="kecamatan">Kecamatan</option>
            <option value="kabupaten">Kabupaten</option>
            <option value="provinsi">Provinsi</option>
            <option value="nasional">Nasional</option>
            <option value="internasional">Internasional</option>
        </select>
    </div>
    
    <div>
        <label class="block mb-1">Nilai Prestasi</label>
        <input type="number" step="0.1" name="prestasi" id="prestasi" class="input input-bordered w-full text-black" readonly>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectEl = document.getElementById('siswa-select');

    // Cek apakah Tom Select belum diinisialisasi
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

    // Script updatePrestasi tetap jalan
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
});
</script>
@endsection
