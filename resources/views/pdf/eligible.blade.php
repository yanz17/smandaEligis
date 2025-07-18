<!DOCTYPE html>
<html>
<head>
    <title>Data Eligible {{ $jurusan }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2, h4 { text-align: center; margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background-color: #eee; text-align: center}
        td { text-align: left; }
    </style>
</head>
<body>
    <h2>Daftar Eligible SNBP Tahun Ajaran 2024/2025</h2>
    <h4>Jurusan {{ $jurusan }} - SMAN 2 Kuningan</h4>
    <p style="text-align: center;">Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d F Y') }}</p>

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
            @foreach ($data as $row)
            <tr>
                <td>{{ $row->siswa->id }}</td>
                <td>{{ $row->siswa->nama }}</td>
                <td>{{ $row->siswa->kelas->nama_kelas ?? '-' }}</td>
                <td>{{ number_format($row->hasil_akhir, 4) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
