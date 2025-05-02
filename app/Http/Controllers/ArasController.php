<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nilai;
use App\Models\Eligible;
use App\Models\Siswa;

class ArasController extends Controller
{
    public function getPerhitunganLengkap()
    {
        $data = Nilai::with('siswa')->get();
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
            $siswaIds[] = $nilai->siswa->id; // Simpan juga ID
        }

        $weights = [0.18, 0.18, 0.18, 0.18, 0.18, 0.10];

        $normalized = $this->normalize($matrix);
        $weighted = $this->applyWeights($normalized, $weights);
        $optimal = $this->getOptimalValues($weighted);
        $utility = $this->getUtilityRate($optimal);

        $results = [];
        foreach ($siswaList as $index => $nama) {
            $results[] = [
                'siswa_id' => $siswaIds[$index],
                'nama' => $nama,
                'nilai' => $utility[$index],
                'peringkat' => 0,
            ];
        }

        usort($results, fn($a, $b) => $b['nilai'] <=> $a['nilai']);

        foreach ($results as $i => &$res) {
            $res['peringkat'] = $i + 1;
        }

        return [
            'matrix' => $matrix,
            'normalized' => $normalized,
            'weighted' => $weighted,
            'optimal' => $optimal,
            'utility' => $utility,
            'peringkat' => $results,
            'siswa' => $siswaList,
        ];
    }

    public function simpanEligibleTeratas()
    {
        $hasil = $this->getPerhitunganLengkap();
        $peringkat = $hasil['peringkat'];

        $jumlahTeratas = ceil(count($peringkat) * 0.4);
        $teratas = array_slice($peringkat, 0, $jumlahTeratas);

        Eligible::truncate(); // hapus data lama

        foreach ($teratas as $item) {
            if (!isset($item['nilai']) || !isset($item['siswa_id'])) continue;

            //dd($item['nama'], $item['nilai']);
            Eligible::create([
                'siswa_id' => $item['siswa_id'],
                'hasil_akhir' => $item['nilai'],
            ]);
        }
    }

    private function normalize($matrix)
    {
        $normalized = [];
        $cols = count($matrix[0]);

        for ($j = 0; $j < $cols; $j++) {
            $colSum = array_sum(array_column($matrix, $j));
            foreach ($matrix as $i => $row) {
                $normalized[$i][$j] = $colSum > 0 ? $row[$j] / $colSum : 0;
            }
        }

        return $normalized;
    }

    private function applyWeights($normalized, $weights)
    {
        $weighted = [];

        foreach ($normalized as $i => $row) {
            foreach ($row as $j => $value) {
                $weighted[$i][$j] = $value * $weights[$j];
            }
        }

        return $weighted;
    }

    private function getOptimalValues($weighted)
    {
        return array_map('array_sum', $weighted);
    }

    private function getUtilityRate($optimal)
    {
        $max = max($optimal) ?: 1; // Hindari pembagian 0
        

        return array_map(fn($value) => $value / $max, $optimal);
    }
}
