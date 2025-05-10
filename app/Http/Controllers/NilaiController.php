<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Nilai;
use App\Imports\NilaiImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Nilai::with('siswa.kelas');
        $kelas = Kelas::all();

        if ($request->search) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->kelas_id) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }

        $totalNilai = $query->count();

        if ($totalNilai >= 50) {
            $nilais = $query->paginate(10);
        } else {
            $nilais = $query->get();
        }

        return view('dashboard.index', ['tab' => 'nilai'], compact('nilais', 'kelas', 'totalNilai'));
    }

    public function create()
    {
        $siswas = Siswa::whereDoesntHave('nilai')->with('kelas')->get();
        return view('nilai.create', compact('siswas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'sem_1' => 'nullable|numeric',
            'sem_2' => 'nullable|numeric',
            'sem_3' => 'nullable|numeric',
            'sem_4' => 'nullable|numeric',
            'sem_5' => 'nullable|numeric',
            'prestasi' => 'nullable|numeric',
        ]);

        if (Nilai::where('siswa_id', $request->siswa_id)->exists()) {
            return redirect()->back()->with('error', 'Siswa ini sudah memiliki nilai.');
        }

        Nilai::create($request->all());

        return redirect()->route('dashboard.index', ['tab' => 'nilai'])->with('success', 'Nilai berhasil ditambahkan!');
    }

    public function edit(Nilai $nilai)
    {
        $siswas = Siswa::with('kelas')->get();
        return view('nilai.edit', compact('nilai', 'siswas'));
    }

    public function update(Request $request, Nilai $nilai)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'sem_1' => 'nullable|numeric',
            'sem_2' => 'nullable|numeric',
            'sem_3' => 'nullable|numeric',
            'sem_4' => 'nullable|numeric',
            'sem_5' => 'nullable|numeric',
            'prestasi' => 'nullable|numeric',
        ]);

        $nilai->update($request->all());

        return redirect()->route('dashboard.index', ['tab' => 'nilai'])->with('success', 'Nilai berhasil diupdate!');
    }

    public function destroy(Nilai $nilai)
    {
        $nilai->delete();

        return redirect()->route('dashboard.index', ['tab' => 'nilai'])->with('success', 'Nilai berhasil dihapus!');
    }
    
    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xls,xlsx']);
        Excel::import(new NilaiImport, $request->file('file'));
        return redirect()->back()->with('success', 'Import nilai berhasil!');
    }
}
