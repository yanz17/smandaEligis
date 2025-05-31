<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\EligibleMipa;
use App\Models\EligibleIps;
use Illuminate\Http\Request;

class CekStatusController extends Controller
{
    public function form()
    {
        return view('cekStatus');
    }

    public function cek(Request $request)
    {
        $request->validate([
            'id' => 'required|digits:10',
            'tanggal_lahir' => 'required|date',
        ]);
    
        $siswa = Siswa::with('kelas')
            ->where('id', $request->id)
            ->where('tanggal_lahir', $request->tanggal_lahir)
            ->first();
    
        if (!$siswa) {
            return back()->withErrors([
                'id' => 'Data siswa tidak ditemukan atau tanggal lahir salah.'
            ])->withInput();
        }
    
        $jurusan = strtolower($siswa->kelas->jurusan ?? '');
    
        if ($jurusan === 'mipa') {
            $eligible = EligibleMipa::where('siswa_id', $siswa->id)->first();
        } elseif ($jurusan === 'ips') {
            $eligible = EligibleIps::where('siswa_id', $siswa->id)->first();
        } else {
            $eligible = null;
        }
    
        return view('cekStatusHasil', [
            'siswa' => $siswa,
            'eligible' => $eligible !== null
        ]);
    }
    
    
}
