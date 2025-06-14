@props(['data'])

<table class="table w-full" x-bind:class="isDarkMode ? 'text-gray-300' : 'text-black'">
    <thead>
        <tr x-bind:class="isDarkMode ? 'text-gray-300' : 'text-black'">
            <th>Peringkat</th>
            <th>NIS</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Hasil Akhir</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data as $item)
            <tr>
                <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                <td>{{ $item->siswa->id }}</td>
                <td>{{ $item->siswa->nama }}</td>
                <td>{{ $item->siswa->kelas->nama_kelas }}</td>
                <td>{{ number_format($item->hasil_akhir, 4) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="2" class="text-center text-gray-500">Tidak ada data eligible.</td>
            </tr>
        @endforelse
    </tbody>
</table>
{{ $data->withQueryString()->links() }}