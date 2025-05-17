@props(['siswas', 'sort'])

<div>
    <a href="{{ route('siswa.create') }}" class="btn btn-primary mb-4">Tambah Siswa</a>

    <table class="table w-full">
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>
                    @if(auth()->user()->isGuruBK())
                    <a href="{{ route('dashboard.index', array_merge(request()->all(), ['sort' => ($sort == 'asc' ? 'desc' : 'asc'), 'tab' => 'siswa'])) }}">
                        Nama
                        @if($sort == 'asc')
                            ▲
                        @else
                            ▼
                        @endif
                    </a>
                    @endif

                    @if(auth()->user()->isWaliKelas())
                    <a href="{{ route('dashboard.wakel', array_merge(request()->all(), ['sort' => ($sort == 'asc' ? 'desc' : 'asc'), 'tab' => 'siswa'])) }}">
                        Nama
                        @if($sort == 'asc')
                            ▲
                        @else
                            ▼
                        @endif
                    </a>
                    @endif
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
                    @if(auth()->user()->isGuruBK())
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
                    @endif

                    @if(auth()->user()->isWaliKelas())
                        <td>
                            <form action="{{ route('siswa.requestDelete', $siswa->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin request hapus?')">Request Delete</button>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('changeRequests.editRequest', ['model' => 'siswa', 'id' => $siswa->id]) }}" class="btn btn-warning btn-sm">
                                Request Edit
                            </a>
                        </td>
                    @endif
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
