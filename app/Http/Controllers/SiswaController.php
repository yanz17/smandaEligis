<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

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

        return view('dashboard.index', compact('siswas', 'kelas', 'totalSiswas'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        return view('siswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        Siswa::create($request->all());

        return redirect()->route('dashboard.index', ['tab' => 'siswa'])->with('success', 'Siswa berhasil ditambahkan!');
    }

    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::all();
        return view('siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
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
}
