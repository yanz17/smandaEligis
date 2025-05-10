@props(['siswas', 'sort'])

<div>
    <a href="{{ route('siswa.create') }}" class="btn btn-primary mb-4">Tambah Siswa</a>

    <table class="table w-full">
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>
                    <a href="{{ route('dashboard.index', array_merge(request()->all(), ['sort' => ($sort == 'asc' ? 'desc' : 'asc'), 'tab' => 'nilai'])) }}">
                        Nama
                        @if($sort == 'asc')
                            ▲
                        @else
                            ▼
                        @endif
                    </a>
                </th>
                <th>Tanggal Lahir</th>
                <th>Kelas</th>
                <th colspan="2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($siswas as $index => $siswa)
                <tr>
                    <td>{{ ($siswas->currentPage() - 1) * $siswas->perPage() + $index + 1 }}</td>
                    <td>{{ $siswa->id }}</td>
                    <td>{{ $siswa->nama }}</td>
                    <td>{{ $siswa->tanggal_lahir ?? '-' }}</td>
                    <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                    <td>
                        <a href="{{ route('siswa.edit', $siswa->id) }}" class="btn btn-sm btn-info">Edit</a>
                    </td>
                    <td>
                        <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST" onsubmit="return confirm('Yakin mau hapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-error">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data siswa.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    {{ $siswas->withQueryString()->links() }}
</div>
