<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Nilai;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Eligible;
use App\Models\Peringkat;
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
        $sort = $request->input('sort', 'asc'); // default asc
        $tab = $request->input('tab', 'dashboard'); // <-- ambil tab aktif
        $kelas = Kelas::all();
        $users = null;
        $siswas = null;
        $nilais = null;

        $hasilAras = (new ArasController())->getPerhitunganLengkap();
        $peringkat = $hasilAras['peringkat'];

        if ($tab === 'user' || $tab === 'dashboard') {
            $users = User::query()
                ->paginate(10, ['*'], 'user_page')
                ->appends(request()->except('page'));
        }

        if ($tab === 'siswa' || $tab === 'dashboard') {
            $siswas = Siswa::with('kelas')
                ->when($searchSiswa, function($query, $searchSiswa) {
                    $query->where('nama', 'like', "%$searchSiswa%");
                })
                ->when($kelasId, function($query, $kelasId) {
                    $query->where('kelas_id', $kelasId);
                })
                ->orderBy('nama', $sort)
                ->paginate(10, ['*'], 'siswa_page')
                ->appends(request()->except('page'));
        }

        if ($tab === 'nilai' || $tab === 'dashboard') {
            $nilais = Nilai::with('siswa.kelas')
                ->when($searchNilai, function($query, $searchNilai) {
                    $query->whereHas('siswa', function($q) use ($searchNilai) {
                        $q->where('nama', 'like', "%$searchNilai%");
                    });
                })
                ->when($kelasId, function($query, $kelasId) {
                    $query->whereHas('siswa', function($q) use ($kelasId) {
                        $q->where('kelas_id', $kelasId);
                    });
                })
                ->orderBy('id', $sort)
                ->paginate(10, ['*'], 'nilai_page')
                ->appends(request()->except('page'));
                //dd($nilais);
        }

        if ($tab === 'peringkat' || $tab === 'dashboard') {
            $langkah = $request->input('langkah');
            $search = $request->input('search_siswa');
        
            if ($search) {
                $filtered = [];
        
                foreach ($hasilAras['siswa'] as $i => $nama) {
                    if (stripos($nama, $search) !== false) {
                        $filtered['siswa'][] = $nama;
        
                        foreach (['matrix', 'normalized', 'weighted', 'optimal', 'utility', 'peringkat'] as $step) {
                            if (isset($hasilAras[$step][$i])) {
                                $filtered[$step][] = $hasilAras[$step][$i];
                            }
                        }
                    }
                }
        
                $hasilAras = $filtered + ['siswa' => []]; // pastikan selalu ada key
            }
        }

        (new ArasController())->simpanEligibleTeratas();
        $eligibles = Eligible::with('siswa')->orderByDesc('hasil_akhir')->get();


        //dd($hasilAras);
        return view('dashboard.index', compact('siswas', 'nilais', 'users', 'searchSiswa', 'searchNilai', 'kelas', 'kelasId', 'sort', 'peringkat', 'hasilAras','eligibles'));
    }
}
