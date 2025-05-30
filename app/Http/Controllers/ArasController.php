<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\EligibleMipa;
use App\Models\EligibleIps;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ArasController extends Controller
{
    public function hitungDanSimpanEligible()
    {
        $this->prosesJurusan('MIPA');
        $this->prosesJurusan('IPS');
    }

    private function prosesJurusan(string $jurusan)
    {
        $data = Nilai::whereHas('siswa.kelas', fn($q) => $q->where('jurusan', $jurusan))
        ->with('siswa.kelas')
        ->get();

        if ($data->isEmpty()) return;

        $matrix = [];
        $siswaList = [];

        foreach ($data as $nilai) {
            $matrix[] = [
                $nilai->sem_1,
                $nilai->sem_2,
                $nilai->sem_3,
                $nilai->sem_4,
                $nilai->sem_5,
                $nilai->prestasi
            ];
            $siswaList[] = [
                'id' => $nilai->siswa->id,
                'nama' => $nilai->siswa->nama
            ];
        }

        $weights = [18, 18, 18, 18, 18, 10];
        $normalized = $this->normalize($matrix);
        $weighted = $this->applyWeights($normalized, $weights);
        $optimal = array_map('array_sum', $weighted);
        $max = max($optimal) ?: 1;
        $utility = array_map(fn($val) => $val / $max, $optimal);

        $results = [];
        foreach ($siswaList as $i => $siswa) {
            $results[] = [
                'siswa_id' => $siswa['id'],
                'nama' => $siswa['nama'],
                'nilai' => $utility[$i],
            ];
        }

        usort($results, fn($a, $b) => $b['nilai'] <=> $a['nilai']);
        $jumlah = ceil(count($results) * 0.4);
        $teratas = array_slice($results, 0, $jumlah);

        if ($jurusan === 'MIPA') {
            EligibleMipa::truncate();
            foreach ($teratas as $item) {
                EligibleMipa::create([
                    'siswa_id' => $item['siswa_id'],
                    'hasil_akhir' => $item['nilai'],
                ]);
            }
        } else {
            EligibleIps::truncate();
            foreach ($teratas as $item) {
                EligibleIps::create([
                    'siswa_id' => $item['siswa_id'],
                    'hasil_akhir' => $item['nilai'],
                ]);
            }
        }
    }

    private function normalize($matrix)
    {
        $normalized = [];
        $cols = count($matrix[0]);

        for ($j = 0; $j < $cols; $j++) {
            $sum = array_sum(array_column($matrix, $j));
            foreach ($matrix as $i => $row) {
                $normalized[$i][$j] = $sum > 0 ? $row[$j] / $sum : 0;
            }
        }
        return $normalized;
    }

    private function applyWeights($normalized, $weights)
    {
        $weighted = [];
        foreach ($normalized as $i => $row) {
            foreach ($row as $j => $val) {
                $weighted[$i][$j] = $val * $weights[$j];
            }
        }
        return $weighted;
    }

    private function paginate(Collection $items, int $perPage, int $currentPage)
    {
        $offset = ($currentPage - 1) * $perPage;
        return new LengthAwarePaginator(
            $items->slice($offset, $perPage)->values(),
            $items->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    public function getPerhitunganLengkapPerJurusan(string $jurusan, string $search = null)
    {
        $query = Nilai::whereHas('siswa.kelas', fn($q) => $q->where('jurusan', $jurusan))
            ->orderBy('siswa_id') 
            ->with('siswa.kelas');

        // Tambahkan filter search jika ada
        if ($search) {
            $query->whereHas('siswa', fn($q) => $q->where('nama', 'like', "%$search%"));
        }

        $data = $query->get();

        if ($data->isEmpty()) return [];

        $matrix = [];
        $siswaList = [];

        foreach ($data as $nilai) {
            $matrix[] = [
                $nilai->sem_1,
                $nilai->sem_2,
                $nilai->sem_3,
                $nilai->sem_4,
                $nilai->sem_5,
                $nilai->prestasi
            ];
            $siswaList[] = [
                'id' => $nilai->siswa->id,
                'nama' => $nilai->siswa->nama
            ];
        }

        $weights = [18, 18, 18, 18, 18, 10];
        $normalized = $this->normalize($matrix);
        $weighted = $this->applyWeights($normalized, $weights);
        $optimal = array_map('array_sum', $weighted);
        $max = max($optimal) ?: 1;
        $utility = array_map(fn($val) => $val / $max, $optimal);

        // Bentuk peringkat
        $peringkat = [];
        foreach ($siswaList as $i => $siswa) {
            $peringkat[] = [
                'siswa_id' => $siswa['id'],
                'nama' => $siswa['nama'],
                'nilai' => $utility[$i],
            ];
        }

        // Urutkan peringkat
        usort($peringkat, fn($a, $b) => $b['nilai'] <=> $a['nilai']);

        foreach ($peringkat as $i => &$item) {
            $item['peringkat'] = $i + 1;
        }

        // Gabungkan nama siswa + data di setiap langkah
        $gabungData = function (array $source) use ($siswaList) {
            return collect($source)->map(function ($row, $i) use ($siswaList) {
                return [
                    'nama' => $siswaList[$i]['nama'] ?? 'Tanpa Nama',
                    'data' => is_array($row) ? $row : [$row],
                ];
            });
        };

        // Pagination
        $currentPage = request()->get('page', 1);
        $perPage = 10;

        return [
            'matrix' => $this->paginate($gabungData($matrix), $perPage, $currentPage),
            'normalized' => $this->paginate($gabungData($normalized), $perPage, $currentPage),
            'weighted' => $this->paginate($gabungData($weighted), $perPage, $currentPage),
            'optimal' => $this->paginate($gabungData($optimal), $perPage, $currentPage),
            'utility' => $this->paginate($gabungData($utility), $perPage, $currentPage),
            'peringkat' => $this->paginate(collect($peringkat), $perPage, $currentPage),
        ];
    }
}