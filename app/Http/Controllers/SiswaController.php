<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaImport;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::query()->with('kelas');
        $kelas = Kelas::all();

        if ($request->search) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        if ($request->kelas_id) {
            $query->where('kelas_id', $request->kelas_id);
        }

        // hitung total data
        $totalSiswas = $query->count();

        if ($totalSiswas >= 50) {
            $siswas = $query->paginate(10);
        } else {
            $siswas = $query->get();
        }

        $user = Auth::user();
        if($user->role === 'gurubk'){
            return view('dashboard.index', compact('siswas', 'kelas', 'totalSiswas'));
        }elseif($user->role === 'wakel'){
            return view('dashboard.wakel', compact('siswas', 'kelas', 'totalSiswas'));
        }
        abort(403, 'Unauthorized action.');
    }

    public function create()
    {
        $kelas = Kelas::all();
        return view('siswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|string|max:10',
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        Siswa::create($request->all());

        $user = Auth::user();
        if($user->role === 'gurubk'){
            return redirect()->route('dashboard.index', ['tab' => 'siswa'])->with('success', 'Siswa berhasil ditambahkan!');
        }elseif($user->role === 'wakel'){
            return redirect()->route('dashboard.wakel', ['tab' => 'siswa'])->with('success', 'Siswa berhasil ditambahkan!');
        }

    }

    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::all();
        return view('siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'id' => 'required|integer|max:10',
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $siswa->update($request->all());

        return redirect()->route('dashboard.index', ['tab' => 'siswa'])->with('success', 'Siswa berhasil diupdate!');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();

        return redirect()->route('dashboard.index', ['tab' => 'siswa'])->with('success', 'Siswa berhasil dihapus!');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,csv']);
        Excel::import(new SiswaImport, $request->file('file'));
        return back()->with('success', 'Import berhasil');
    }
}