<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Nilai;
use App\Imports\NilaiImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::all();
        $sortOrder = $request->get('sort', 'asc');

        $query = Nilai::select('nilais.*', 'siswas.nama as siswa_nama', 'kelas.nama_kelas as kelas_namakelas')
            ->join('siswas', 'siswas.id', '=', 'nilais.siswa_id')
            ->join('kelas', 'kelas.id', '=', 'siswas.kelas_id')
            ->where(function ($q) use ($request) {
                if ($request->search) {
                    $q->where('siswas.nama', 'like', '%' . $request->search . '%');
                }
                if ($request->kelas_id) {
                    $q->where('siswas.kelas_id', $request->kelas_id);
                }
            })
            ->orderBy('siswas.nama', $sortOrder);

        $nilais = $query->paginate(10);
        $totalNilai = $nilais->total();

        $user = Auth::user();
        if ($user->role === 'gurubk') {
            return view('dashboard.index', ['tab' => 'nilai'], compact('nilais', 'kelas', 'totalNilai', 'sortOrder'));
        } elseif ($user->role === 'wakel') {
            return view('dashboard.wakel', ['tab' => 'nilai'], compact('nilais', 'kelas', 'totalNilai', 'sortOrder'));
        }

        abort(403, 'Unauthorized action.');
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
            return redirect()->back()->withInput()->with('error', 'Siswa ini sudah memiliki nilai.');
        }

        $data = $request->only(['siswa_id', 'sem_1', 'sem_2', 'sem_3', 'sem_4', 'sem_5', 'prestasi']);

        // Ganti null dengan 0 (atau angka default lain jika perlu)
        foreach (['sem_1', 'sem_2', 'sem_3', 'sem_4', 'sem_5', 'prestasi'] as $field) {
            $data[$field] = round($data[$field] ?? 0, 2);
        }

        Nilai::create($data);

        $user = Auth::user();
        if ($user->role === 'gurubk') {
            return redirect()->route('dashboard.index', ['tab' => 'nilai'])->with('success', 'Nilai berhasil ditambahkan!');
        } elseif ($user->role === 'wakel') {
            return redirect()->route('dashboard.wakel', ['tab' => 'nilai'])->with('success', 'Nilai berhasil ditambahkan!');
        }

        abort(403, 'Unauthorized action.');
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

        foreach (['sem_1', 'sem_2', 'sem_3', 'sem_4', 'sem_5', 'prestasi'] as $field) {
            $data[$field] = round($data[$field] ?? 0, 2);
        }

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

    public function requestEdit(Request $request, Nilai $nilai)
    {
        $user = Auth::user();
        if ($user->role !== 'wakel') abort(403);

        $request->validate([
            'sem_1' => 'nullable|numeric',
            'sem_2' => 'nullable|numeric',
            'sem_3' => 'nullable|numeric',
            'sem_4' => 'nullable|numeric',
            'sem_5' => 'nullable|numeric',
            'prestasi' => 'nullable|numeric',
        ]);

        \App\Models\ChangeRequest::create([
            'user_id' => $user->id,
            'model_type' => 'Nilai',
            'model_id' => $nilai->id,
            'action' => 'edit',
            'data' => $request->only(['sem_1', 'sem_2', 'sem_3', 'sem_4', 'sem_5', 'prestasi']),
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Permintaan edit nilai telah dikirim, tunggu persetujuan Guru BK.');
    }

    public function requestDelete(Nilai $nilai)
    {
        $user = Auth::user();
        if ($user->role !== 'wakel') abort(403);

        \App\Models\ChangeRequest::create([
            'user_id' => $user->id,
            'model_type' => 'Nilai',
            'model_id' => $nilai->id,
            'action' => 'delete',
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Permintaan hapus nilai telah dikirim, tunggu persetujuan Guru BK.');
    }

}