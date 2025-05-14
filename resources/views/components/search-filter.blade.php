@props([
    'kelas',
    'tab' => 'siswa',
    'searchName' => '',
    'selectedKelas' => '',
])

<form method="GET" class="mb-4 flex flex-wrap gap-2">
    <input type="hidden" name="tab" value="{{ $tab }}">

    {{-- Input pencarian nama --}}
    <input
        type="text"
        name="search_{{ $tab }}"
        placeholder="Cari {{ $tab }}..."
        value="{{ $searchName }}"
        class="input input-bordered"
    >

    {{-- Filter berdasarkan kelas --}}
    @if(!auth()->user()->isWaliKelas())
        <select name="kelas_id" class="select select-bordered">
            <option value="">Semua Kelas</option>
            @foreach($kelas as $k)
                <option value="{{ $k->id }}" {{ $selectedKelas == $k->id ? 'selected' : '' }}>
                    {{ $k->nama_kelas }}
                </option>
            @endforeach
        </select>
    @endif

    {{ $slot }}

    <button type="submit" class="btn btn-primary">Cari</button>

    @if(auth()->user()->isGuruBK())
        <a href="{{ route('dashboard.index', ['tab' => $tab]) }}" class="btn btn-outline">Reset</a>
    @endif
    @if(auth()->user()->isWaliKelas())
        <a href="{{ route('dashboard.wakel', ['tab' => $tab]) }}" class="btn btn-outline">Reset</a>
    @endif
    
</form>

