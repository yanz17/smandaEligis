<!DOCTYPE html>
<html>
<head>
    <title>Data Eligible {{ $jurusan }}</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; padding: 6px; }
    </style>
</head>
<body>
    <h2>Data Siswa Eligible {{ $jurusan }}</h2>
    <table>
        <thead>
            <tr>
                <th>NIS</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Hasil Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{ $item->siswa->id }}</td>
                    <td>{{ $item->siswa->nama }}</td>
                    <td>{{ $item->siswa->kelas->nama_kelas ?? '-' }}</td>
                    <td>{{ $item->hasil_akhir }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
