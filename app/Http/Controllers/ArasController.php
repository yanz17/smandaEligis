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

    public function getPerhitunganLengkapPerJurusan(string $jurusan)
    {
        $data = Nilai::whereHas('siswa.kelas', fn($q) => $q->where('jurusan', $jurusan))
            ->orderBy('siswa_id') 
            ->with('siswa.kelas')->get();
    
        if ($data->isEmpty()) return [];
    
        $matrix = [];
        $siswaList = [];
        $siswaIds = [];
    
        foreach ($data as $nilai) {
            $matrix[] = [
                $nilai->sem_1,
                $nilai->sem_2,
                $nilai->sem_3,
                $nilai->sem_4,
                $nilai->sem_5,
                $nilai->prestasi
            ];
            $siswaList[] = $nilai->siswa->nama;
            $siswaIds[] = $nilai->siswa->id;
        }
    
        $weights = [18, 18, 18, 18, 18, 10];
        $normalized = $this->normalize($matrix);
        $weighted = $this->applyWeights($normalized, $weights);
        $optimal = array_map('array_sum', $weighted);
        $max = max($optimal) ?: 1;
        $utility = array_map(fn($val) => $val / $max, $optimal);
    
        $peringkat = [];
        foreach ($siswaList as $i => $nama) {
            $peringkat[] = [
                'siswa_id' => $siswaIds[$i],
                'nama' => $nama,
                'nilai' => $utility[$i],
                'peringkat' => 0,
            ];
        }
    
        usort($peringkat, fn($a, $b) => $b['nilai'] <=> $a['nilai']);
        foreach ($peringkat as $i => &$item) {
            $item['peringkat'] = $i + 1;
        }
    
        $currentPage = request()->get('page', 1);
        $perPage = 10;
    
        $offset = ($currentPage - 1) * $perPage;
        
        return [
            'matrix' => new LengthAwarePaginator(array_slice($matrix, $offset, $perPage), count($matrix), $perPage, $currentPage),
            'normalized' => new LengthAwarePaginator(array_slice($normalized, $offset, $perPage), count($normalized), $perPage, $currentPage),
            'weighted' => new LengthAwarePaginator(array_slice($weighted, $offset, $perPage), count($weighted), $perPage, $currentPage),
            'optimal' => new LengthAwarePaginator(array_slice($optimal, $offset, $perPage), count($optimal), $perPage, $currentPage),
            'utility' => new LengthAwarePaginator(array_slice($utility, $offset, $perPage), count($utility), $perPage, $currentPage),
            'peringkat' => new LengthAwarePaginator(array_slice($peringkat, $offset, $perPage), count($peringkat), $perPage, $currentPage),
            'siswa' => new LengthAwarePaginator(array_slice($siswaList, $offset, $perPage), count($siswaList), $perPage, $currentPage),
        ];
    }

}
