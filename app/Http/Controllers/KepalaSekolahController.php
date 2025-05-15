<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EligibleMipa;
use App\Models\EligibleIps;
use Illuminate\Support\Facades\Auth;

class KepalaSekolahController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'eligible');
        $jurusan = $request->input('jurusan', 'MIPA');
        $sort = $request->get('sort', 'desc');
        $search = $request->get('search_siswa');

        $eligiblesMipa = null;
        $eligiblesIps = null;

        if ($jurusan === 'MIPA') {
            $eligiblesMipa = EligibleMipa::with(['siswa.kelas'])
                ->when($search, fn($q) =>
                    $q->whereHas('siswa', fn($q2) =>
                        $q2->where('nama', 'like', "%$search%")
                    )
                )
                ->orderBy('hasil_akhir', $sort)
                ->paginate(10, ['*'], 'mipa_page')
                ->appends(request()->except('page'));
        } else {
            $eligiblesIps = EligibleIps::with(['siswa.kelas'])
                ->when($search, fn($q) =>
                    $q->whereHas('siswa', fn($q2) =>
                        $q2->where('nama', 'like', "%$search%")
                    )
                )
                ->orderBy('hasil_akhir', $sort)
                ->paginate(10, ['*'], 'ips_page')
                ->appends(request()->except('page'));
        }

        return view('dashboard.kepsek', [
            'eligiblesMipa' => $eligiblesMipa,
            'eligiblesIps' => $eligiblesIps,
            'jurusan' => $jurusan,
            'sort' => $sort,
            'search' => $search,
            'tab' => $tab
        ]);
    }
}
