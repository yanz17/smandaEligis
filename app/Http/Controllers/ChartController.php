<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Nilai;
use App\Models\Kelas;
use App\Models\EligibleMipa;
use App\Models\EligibleIps;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    // Chart: Jumlah Siswa per Jurusan
    public function siswaPerJurusan()
    {
        $jumlah = Siswa::with('kelas')
            ->get()
            ->groupBy(fn($siswa) => $siswa->kelas->jurusan ?? 'Tidak diketahui')
            ->map(fn($group) => $group->count());

        return response()->json($jumlah);
    }

    // Chart: Jumlah Eligible per Jurusan
    public function eligiblePerJurusan()
    {
        return response()->json([
            'MIPA' => EligibleMipa::count(),
            'IPS' => EligibleIps::count()
        ]);
    }

    // Chart: Jumlah Siswa per Kelas
    public function siswaPerKelas()
    {
        $kelas = Kelas::with('siswas')->get();

        $data = [];

        foreach ($kelas as $kls) {
            $jurusan = $kls->jurusan ?? 'Tidak diketahui';
            $data[$jurusan][$kls->nama_kelas] = $kls->siswas->count();
        }

        return response()->json($data);
    }

    public function eligiblePerKelasGabungan()
    {
        $mipa = EligibleMipa::with('siswa.kelas')->get();
        $ips = EligibleIps::with('siswa.kelas')->get();

        $gabungan = $mipa->merge($ips);

        $kelasData = [];
        foreach ($gabungan as $item) {
            $kelas = $item->siswa->kelas;
            if ($kelas) {
                $kelasData[$kelas->nama_kelas] = [
                    'jurusan' => $kelas->jurusan ?? 'Tidak diketahui',
                ];
            }
        }

        $data = $gabungan->groupBy(function ($item) {
            return $item->siswa->kelas->nama_kelas ?? 'Tidak diketahui';
        })->map(function ($group) {
            return $group->count();
        });

        $sorted = collect($data)->sortKeysUsing(function ($a, $b) use ($kelasData) {
            $jurusanA = $kelasData[$a]['jurusan'] ?? 'Z';
            $jurusanB = $kelasData[$b]['jurusan'] ?? 'Z';

            preg_match('/\d+/', $a, $matchA);
            preg_match('/\d+/', $b, $matchB);

            $angkaA = isset($matchA[0]) ? (int) $matchA[0] : 999;
            $angkaB = isset($matchB[0]) ? (int) $matchB[0] : 999;

            $prefixA = $jurusanA === 'MIPA' ? 0 : ($jurusanA === 'IPS' ? 1 : 2);
            $prefixB = $jurusanB === 'MIPA' ? 0 : ($jurusanB === 'IPS' ? 1 : 2);

            return ($prefixA * 1000 + $angkaA) <=> ($prefixB * 1000 + $angkaB);
        });

        // Tambahkan warna berdasarkan jurusan
        $result = [];
        foreach ($sorted as $kelas => $jumlah) {
            $jurusan = $kelasData[$kelas]['jurusan'] ?? 'Tidak diketahui';
            $color = match ($jurusan) {
                'MIPA' => '#60a5fa', // biru
                'IPS' => '#f87171', // merah
                default => '#a3a3a3' // abu
            };

            $result[] = [
                'kelas' => $kelas,
                'jumlah' => $jumlah,
                'color' => $color,
            ];
        }

        return response()->json($result);
    }


    public function rataRataKriteria()
    {
        $rata = \App\Models\Nilai::selectRaw('
            AVG(sem_1) as sem_1,
            AVG(sem_2) as sem_2,
            AVG(sem_3) as sem_3,
            AVG(sem_4) as sem_4,
            AVG(sem_5) as sem_5,
            AVG(prestasi) as prestasi
        ')->first();

        return response()->json([
            'sem_1' => round($rata->sem_1 ?? 0, 2),
            'sem_2' => round($rata->sem_2 ?? 0, 2),
            'sem_3' => round($rata->sem_3 ?? 0, 2),
            'sem_4' => round($rata->sem_4 ?? 0, 2),
            'sem_5' => round($rata->sem_5 ?? 0, 2),
            'prestasi' => round($rata->prestasi ?? 0, 2),
        ]);
    }
}
