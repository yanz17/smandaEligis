@props(['hasilAras', 'langkah'])

@if ($langkah && !empty($hasilAras[$langkah]))
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
            @if ($langkah === 'peringkat')
                @foreach ($hasilAras['peringkat'] as $item)
                    <tr>
                        <td class="px-2 py-1">{{ $item['nama'] }}</td>
                        <td class="px-2 py-1">{{ $item['peringkat'] }}</td>
                        <td class="px-2 py-1">{{ round((float) $item['nilai'], 4) }}</td>
                    </tr>
                @endforeach
            @else
                @foreach ($hasilAras['siswa'] as $i => $nama)
                    <tr>
                        <td class="px-2 py-1">{{ $nama }}</td>

                        @php $baris = $hasilAras[$langkah][$i] ?? null; @endphp

                        @if (is_array($baris))
                            @foreach ($baris as $val)
                                <td class="px-2 py-1">{{ is_numeric($val) ? round((float) $val, 4) : $val }}</td>
                            @endforeach
                        @elseif (is_numeric($baris))
                            <td class="px-2 py-1">{{ round((float) $baris, 4) }}</td>
                        @else
                            <td class="px-2 py-1">-</td>
                        @endif
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    {{-- Paginator --}}
    @if(method_exists($hasilAras[$langkah], 'links'))
        <div class="mt-2">
            {{ $hasilAras[$langkah]->withQueryString()->links() }}
        </div>
    @endif
@endif

