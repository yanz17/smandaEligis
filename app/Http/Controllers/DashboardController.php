<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Nilai;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Eligible;
use App\Models\Peringkat;
use App\Models\EligibleMipa;
use App\Models\EligibleIps;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Http\Controllers\ArasController;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
    $searchSiswa = $request->input('search_siswa');
    $searchNilai = $request->input('search_nilai');
    $kelasId = $request->input('kelas_id');
    $sort = $request->input('sort', 'asc');
    $tab = $request->input('tab', 'dashboard');
    $kelas = Kelas::all();
    $users = null;
    $siswas = null;
    $nilais = null;
    $eligiblesMipa = null;
    $eligiblesIps = null;
    $hasilAras = [];

    $aras = new ArasController();
    $hasilArasMipa = [];
    $hasilArasIps = [];
    
    if ($tab === 'peringkat_mipa') {
        $hasilArasMipa = $aras->getPerhitunganLengkapPerJurusan('MIPA');
    }
    
    if ($tab === 'peringkat_ips') {
        $hasilArasIps = $aras->getPerhitunganLengkapPerJurusan('IPS');
    }

    if ($tab === 'user') {
        $users = User::query()
            ->paginate(10, ['*'], 'user_page')
            ->appends(request()->except('page'));
    } elseif ($tab === 'siswa') {
        $siswas = Siswa::with('kelas')
            ->when($searchSiswa, fn($q) => $q->where('nama', 'like', "%$searchSiswa%"))
            ->when($kelasId, fn($q) => $q->where('kelas_id', $kelasId))
            ->orderBy('nama', $sort)
            ->paginate(10, ['*'], 'siswa_page')
            ->appends(request()->except('page'));
    } elseif ($tab === 'nilai') {
        $nilais = Nilai::with('siswa.kelas')
            ->when($searchNilai, fn($query) => $query->whereHas('siswa', fn($q) => $q->where('nama', 'like', "%$searchNilai%")))
            ->when($kelasId, fn($query) => $query->whereHas('siswa', fn($q) => $q->where('kelas_id', $kelasId)))
            ->orderBy('id', $sort)
            ->paginate(10, ['*'], 'nilai_page')
            ->appends(request()->except('page'));
    } elseif ($tab === 'eligible') {
        $aras->hitungDanSimpanEligible();
        $search = $request->input('search_siswa');
        $jurusan = $request->input('jurusan');
    
        if ($jurusan === 'IPS') {
            $eligiblesIps = EligibleIps::with('siswa')
                ->when($search, fn($q) => $q->whereHas('siswa', fn($q2) => $q2->where('nama', 'like', "%$search%")))
                ->orderByDesc('hasil_akhir')
                ->paginate(10, ['*'], 'ips_page')
                ->appends(request()->except('page'));
        } else {
            $eligiblesMipa = EligibleMipa::with('siswa')
                ->when($search, fn($q) => $q->whereHas('siswa', fn($q2) => $q2->where('nama', 'like', "%$search%")))
                ->orderByDesc('hasil_akhir')
                ->paginate(10, ['*'], 'mipa_page')
                ->appends(request()->except('page'));
        }
    }

    return view('dashboard.index', compact(
        'siswas', 'nilais', 'users',
        'searchSiswa', 'searchNilai', 'hasilArasMipa', 'hasilArasIps' , 
        'kelas', 'kelasId', 'sort',
        'eligiblesMipa', 'eligiblesIps'
    ));
    }
}
