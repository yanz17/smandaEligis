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
        $mipa = EligibleMIPA::with('siswa.kelas')->get();
        $ips = EligibleIPS::with('siswa.kelas')->get();

        $gabungan = $mipa->merge($ips);

        $data = $gabungan->groupBy(function ($item) {
            return $item->siswa->kelas->nama_kelas ?? 'Tidak diketahui';
        })->map(function ($group) {
            return $group->count();
        });

        return response()->json($data);
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
