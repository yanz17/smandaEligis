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
    $kelas = Kelas::all();

    // Ambil parameter
    $search = $request->get('search_siswa', '');
    $kelasId = $request->get('kelas_id', '');
    $sortOrder = in_array($request->get('sort'), ['asc', 'desc']) ? $request->get('sort') : 'asc';

    // Bangun query
    $query = Siswa::query()->with('kelas');

    if ($search) {
        $query->where('nama', 'like', '%' . $search . '%');
    }

    if ($kelasId) {
        $query->where('kelas_id', $kelasId);
    }

    $query->orderBy('nama', $sortOrder);

    // Hitung total
    $totalSiswas = $query->count();

    // Ambil data
    if ($totalSiswas >= 50) {
        $siswas = $query->paginate(10)->appends($request->query());
    } else {
        $siswas = $query->get();
    }

    // Kirim semua ke view
    $data = compact('siswas', 'kelas', 'totalSiswas', 'search', 'kelasId', 'sortOrder');

    $user = Auth::user();
    if ($user->role === 'gurubk') {
        return view('dashboard.index', $data);
    } elseif ($user->role === 'wakel') {
        return view('dashboard.wakel', $data);
    }

    abort(403, 'Unauthorized action.');
}


    public function create()
    {
        $user = Auth::user();

        if ($user->role === 'wakel') {
            $kelas = Kelas::where('user_id', $user->id)->get(); // ambil hanya kelas si wakel
        } else {
            $kelas = Kelas::all();
        }

        return view('siswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|string|min:10|unique:siswas,id',
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $user = Auth::user();

        // Validasi tambahan: wakel hanya boleh input siswa ke kelas miliknya
        if ($user->role === 'wakel') {
            $kelasWakel = Kelas::where('user_id', $user->id)->pluck('id')->toArray();
            if (!in_array($request->kelas_id, $kelasWakel)) {
                return redirect()->back()->with('error', 'Anda tidak diizinkan menambahkan siswa ke kelas ini.');
            }
        }

        Siswa::create($request->all());

        if ($user->role === 'gurubk') {
            return redirect()->route('dashboard.index', ['tab' => 'siswa'])->with('success', 'Siswa berhasil ditambahkan!');
        } elseif ($user->role === 'wakel') {
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

        public function requestEdit(Request $request, Siswa $siswa)
    {
        $user = Auth::user();
        if ($user->role !== 'wakel') abort(403);

        $request->validate([
            'id' => 'required',
            'nama' => 'required',
            'kelas_id' => 'required',
            'tanggal_lahir' => 'required',
        ]);

        \App\Models\ChangeRequest::create([
            'user_id' => $user->id,
            'model_type' => 'Siswa',
            'model_id' => $siswa->id,
            'action' => 'edit',
            'data' => $request->only(['nama', 'tanggal_lahir', 'id', 'kelas_id']),
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Permintaan edit nilai telah dikirim, tunggu persetujuan Guru BK.');
    }

    public function requestDelete(Siswa $siswa)
    {
        $user = Auth::user();
        if ($user->role !== 'wakel') abort(403);

        \App\Models\ChangeRequest::create([
            'user_id' => $user->id,
            'model_type' => 'Siswa',
            'model_id' => $siswa->id,
            'action' => 'delete',
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Permintaan hapus nilai telah dikirim, tunggu persetujuan Guru BK.');
    }
}