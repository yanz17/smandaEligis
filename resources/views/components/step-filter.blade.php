@props([
    'search' => '',
    'langkah' => '',
])

<form method="GET" class="mb-4 flex flex-wrap gap-2">
    <input type="hidden" name="tab" value="peringkat">

    <input
        type="text"
        name="search_siswa"
        placeholder="Cari siswa..."
        value="{{ $search }}"
        class="input input-bordered"
    />

    <select name="langkah" class="select select-bordered">
        <option value="">Pilih langkah</option>
        <option value="matrix" {{ $langkah == 'matrix' ? 'selected' : '' }}>Matrix Awal</option>
        <option value="normalized" {{ $langkah == 'normalized' ? 'selected' : '' }}>Normalisasi</option>
        <option value="weighted" {{ $langkah == 'weighted' ? 'selected' : '' }}>Pembobotan</option>
        <option value="optimal" {{ $langkah == 'optimal' ? 'selected' : '' }}>Nilai Optimal</option>
        <option value="utility" {{ $langkah == 'utility' ? 'selected' : '' }}>Utility</option>
        <option value="peringkat" {{ $langkah == 'peringkat' ? 'selected' : '' }}>Peringkat Akhir</option>
    </select>

    <button type="submit" class="btn btn-primary">Terapkan</button>
</form>
