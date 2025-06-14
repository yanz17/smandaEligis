@props(['hasilAras', 'langkah'])

@if ($langkah && !empty($hasilAras[$langkah]))
    <h3 class="mt-4 font-semibold">Langkah: {{ ucfirst($langkah) }}</h3>

    <table class="table w-full mt-2" x-bind:class="isDarkMode ? 'text-gray-300' : 'text-black'">
        <thead>
            <tr x-bind:class="isDarkMode ? 'text-gray-300' : 'text-black'">
                <th class="px-2 py-1">Nama</th>

                @if ($langkah === 'peringkat')
                    <th class="px-2 py-1">Peringkat</th>
                    <th class="px-2 py-1">Skor</th>
                @elseif (
                    $hasilAras[$langkah] instanceof \Illuminate\Pagination\LengthAwarePaginator &&
                    isset($hasilAras[$langkah]->items()[0]) &&
                    is_array($hasilAras[$langkah]->items()[0]) &&
                    array_key_exists('data', $hasilAras[$langkah]->items()[0])
                )
                    @foreach ($hasilAras[$langkah]->items()[0]['data'] as $i => $val)
                        <th class="px-2 py-1">Kolom {{ $i + 1 }}</th>
                    @endforeach
                @else
                    <th class="px-2 py-1">Nilai</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($hasilAras[$langkah] as $item)
                <tr>
                    <td>{{ $item['nama'] ?? '-' }}</td>

                    @if ($langkah === 'peringkat')
                        <td>{{ $item['peringkat'] }}</td>
                        <td>{{ round((float) $item['nilai'], 4) }}</td>
                    @elseif (isset($item['data']) && is_array($item['data']))
                        @foreach ($item['data'] as $val)
                            <td>{{ is_numeric($val) ? round((float) $val, 4) : $val }}</td>
                        @endforeach
                    @elseif (isset($item['data']) && is_numeric($item['data']))
                        <td>{{ round((float) $item['data'], 4) }}</td>
                    @else
                        <td>-</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Paginator --}}
    @if ($hasilAras[$langkah] instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-2">
            {{ $hasilAras[$langkah]->appends(request()->except('page'))->links() }}
        </div>
    @endif
@endif
