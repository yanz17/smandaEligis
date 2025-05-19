

@props(['nilais', 'sort' => 'asc'])

<div>
    <a href="{{ route('nilai.create') }}" class="btn btn-success mb-4">Tambah Nilai</a>

    <table class="table w-full" x-bind:class="isDarkMode ? 'text-gray-300' : 'text-black'">
        <thead>
            <tr x-bind:class="isDarkMode ? 'text-gray-300' : 'text-black'">
                <th>No</th>
                <th>
                @if(auth()->user()->isGuruBK())
                    <a href="{{ route('dashboard.index', array_merge(request()->all(), ['sort' => ($sort == 'asc' ? 'desc' : 'asc'), 'tab' => 'nilai'])) }}">
                        Nama
                        @if($sort == 'asc')
                            ▲
                        @else
                            ▼
                        @endif
                    </a>
                @endif

                @if(auth()->user()->isWaliKelas())
                    <a href="{{ route('dashboard.wakel', array_merge(request()->all(), ['sort' => ($sort == 'asc' ? 'desc' : 'asc'), 'tab' => 'nilai'])) }}">
                        Nama
                        @if($sort == 'asc')
                            ▲
                        @else
                            ▼
                        @endif
                    </a>
                @endif
                </th>
                <th>Sem 1</th>
                <th>Sem 2</th>
                <th>Sem 3</th>
                <th>Sem 4</th>
                <th>Sem 5</th>
                <th>Prestasi</th>
                <th colspan="2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($nilais as $index => $nilai)
                <tr>
                    <td>{{ ($nilais->currentPage() - 1) * $nilais->perPage() + $index + 1 }}</td>
                    <td>{{ $nilai->siswa->nama }}</td>
                    <td>{{ number_format($nilai->sem_1, 2) }}</td>
                    <td>{{ number_format($nilai->sem_2, 2) }}</td>
                    <td>{{ number_format($nilai->sem_3, 2) }}</td>
                    <td>{{ number_format($nilai->sem_4, 2) }}</td>
                    <td>{{ number_format($nilai->sem_5, 2) }}</td>
                    <td>{{ $nilai->prestasi }}</td>
                    @if(auth()->user()->isGuruBK())
                        <td><a href="{{ route('nilai.edit', $nilai->id) }}" class="btn btn-sm btn-info">Edit</a></td>
                        <td>
                            <form action="{{ route('nilai.destroy', $nilai->id) }}" method="POST" onsubmit="return confirm('Yakin mau hapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-error">Hapus</button>
                            </form>
                        </td>
                    @endif

                    @if(auth()->user()->isWaliKelas())
                        <td>
                            <form action="{{ route('nilai.requestDelete', $nilai->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin request hapus?')">Request Delete</button>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('changeRequests.editRequest', ['model' => 'nilai', 'id' => $nilai->id]) }}" class="btn btn-warning btn-sm">
                                Request Edit
                            </a>
                        </td>
                    @endif
                </tr>
            @empty
                <tr><td colspan="11" class="text-center">Tidak ada data nilai.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $nilais->withQueryString()->links() }}
</div>
