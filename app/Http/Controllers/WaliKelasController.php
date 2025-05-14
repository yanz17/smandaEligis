<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EligibleIps;
use App\Models\EligibleMipa;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class WaliKelasController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $kelas = $user->kelas;

        $tab = $request->input('tab', 'siswa');
        $sort = $request->get('sort', 'asc');
        $jurusan = $request->input('jurusan');
        if ($tab === 'eligible' && !$jurusan) {
            $jurusan = 'MIPA';
        }

        // Search tergantung tab
        $search = $request->get('search_' . $tab);

        // Inisialisasi default null
        $siswas = null;
        $nilais = null;
        $eligiblesMipa = null;
        $eligiblesIps = null;

        if ($tab === 'siswa') {
            $siswas = $kelas->siswas()->with('nilai')
                ->when($search, fn($q) => $q->where('nama', 'like', "%$search%"))
                ->orderBy('nama', $sort)
                ->paginate(10, ['*'], 'siswa_page')
                ->appends(request()->except('page'));
        } elseif ($tab === 'nilai') {
            $nilais = Nilai::join('siswas', 'nilais.siswa_id', '=', 'siswas.id')
                ->whereIn('siswas.id', $kelas->siswas->pluck('id'))
                ->when($search, fn($q) => $q->where('siswas.nama', 'like', "%$search%"))
                ->orderBy('siswas.nama', $sort)
                ->select('nilais.*')
                ->with('siswa')
                ->paginate(10, ['*'], 'nilai_page')
                ->appends(request()->except('page'));
        } elseif ($tab === 'eligible') {
            if ($jurusan === 'MIPA') {
                $eligiblesMipa = EligibleMipa::with(['siswa.kelas'])
                    ->when($search, fn($q) =>
                        $q->whereHas('siswa', fn($q2) =>
                            $q2->where('nama', 'like', "%$search%")
                        )
                    )
                    ->orderByDesc('hasil_akhir')
                    ->paginate(10, ['*'], 'mipa_page')
                    ->appends(request()->except('page'));
            } else {
                $eligiblesIps = EligibleIps::with(['siswa.kelas'])
                    ->when($search, fn($q) =>
                        $q->whereHas('siswa', fn($q2) =>
                            $q2->where('nama', 'like', "%$search%")
                        )
                    )
                    ->orderByDesc('hasil_akhir')
                    ->paginate(10, ['*'], 'ips_page')
                    ->appends(request()->except('page'));
            }
        }


        return view('dashboard.wakel', [
            'siswas' => $siswas,
            'nilais' => $nilais,
            'searchSiswa' => $search,
            'eligiblesMipa' => $eligiblesMipa,
            'eligiblesIps' => $eligiblesIps,
            'kelas' => $kelas,
            'sort' => $sort,
            'jurusan' => $jurusan,
            'tab' => $tab,
        ]);
    }


}
