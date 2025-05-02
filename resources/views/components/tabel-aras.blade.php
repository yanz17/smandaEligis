@if($langkah && !empty($hasilAras[$langkah]))
    <h3 class="mt-4 font-semibold">Langkah: {{ ucfirst($langkah) }}</h3>

    <table class="table w-full mt-2">
        <thead>
            <tr>
                <th class="px-2 py-1">Nama</th>
                @if ($langkah === 'peringkat')
                    <th class="px-2 py-1">Peringkat</th>
                    <th class="px-2 py-1">Skor</th>
                @elseif (isset($hasilAras[$langkah][0]) && is_array($hasilAras[$langkah][0]))
                    @foreach ($hasilAras[$langkah][0] as $i => $val)
                        <th class="px-2 py-1">Kolom {{ $i + 1 }}</th>
                    @endforeach
                @else
                    <th class="px-2 py-1">Nilai</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($hasilAras['siswa'] as $i => $nama)
                <tr>
                    <td class="px-2 py-1">{{ $nama }}</td>

                    @php $baris = $hasilAras[$langkah][$i] ?? null; @endphp

                    @if ($langkah === 'peringkat' && is_array($baris) && isset($baris['nilai']))
                        <td class="px-2 py-1">{{ $baris['peringkat'] }}</td>
                        <td class="px-2 py-1">{{ round((float) $baris['nilai'], 4) }}</td>
                    @elseif (is_array($baris))
                        @foreach ($baris as $val)
                            <td class="px-2 py-1">
                                {{ is_numeric($val) ? round((float) $val, 4) : $val }}
                            </td>
                        @endforeach
                    @elseif (is_numeric($baris))
                        <td class="px-2 py-1">{{ round((float) $baris, 4) }}</td>
                    @else
                        <td class="px-2 py-1">-</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
@elseif($langkah)
    <p class="text-gray-500 mt-2">Tidak ada data untuk langkah: <strong>{{ $langkah }}</strong>.</p>
@endif
