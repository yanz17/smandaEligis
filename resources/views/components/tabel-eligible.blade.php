@props(['eligibles'])

<h2 class="text-lg font-bold mt-6">Siswa Eligible (Top 40%)</h2>
<table class="table table-bordered mt-2">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Hasil Akhir</th>
        </tr>
    </thead>
    <tbody>
        @foreach($eligibles as $i => $el)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $el->siswa->nama }}</td>
                <td>{{ number_format($el->hasil_akhir, 4) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>