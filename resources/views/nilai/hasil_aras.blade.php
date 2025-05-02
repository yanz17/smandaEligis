@extends('layouts.dashboard')

@section('title', 'Hasil Perhitungan ARAS')

@section('content')
    <h2 class="text-xl font-semibold mb-4">Hasil Perhitungan ARAS</h2>
    <table class="table w-full">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Skor ARAS</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($results as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row['nama'] }}</td>
                    <td>{{ $row['nilai'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
