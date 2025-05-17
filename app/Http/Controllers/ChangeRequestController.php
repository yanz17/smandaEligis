<?php

namespace App\Http\Controllers;

use App\Models\ChangeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Nilai;

class ChangeRequestController extends Controller
{
     public function index()
    {
        $requests = ChangeRequest::where('status', 'pending')->with('requester')->paginate(10);

        return view('dashboard.index', compact('requests'));
    }

    public function approve(ChangeRequest $changeRequest)
    {
        if (Auth::user()->role !== 'gurubk') abort(403);

        if ($changeRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Permintaan sudah diproses.');
        }

        $modelClass = 'App\\Models\\' . $changeRequest->model_type;
        $model = $modelClass::find($changeRequest->model_id);

        if (!$model) {
            return redirect()->back()->with('error', 'Data asli tidak ditemukan.');
        }

        if ($changeRequest->action === 'edit') {
            $model->update($changeRequest->data);
        } elseif ($changeRequest->action === 'delete') {
            $model->delete();
        }

        $changeRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Permintaan berhasil disetujui.');
    }

    public function reject(ChangeRequest $changeRequest)
    {
        if (Auth::user()->role !== 'gurubk') abort(403);

        if ($changeRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Permintaan sudah diproses.');
        }

        $changeRequest->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Permintaan berhasil ditolak.');
    }

    public function editRequest($model, $id)
    {
        if (!in_array($model, ['siswa', 'nilai'])) abort(404);

        if ($model === 'siswa') {
            $siswa = Siswa::findOrFail($id);
            $kelas = \App\Models\Kelas::all();
            return view('siswa.request_edit', compact('siswa', 'kelas'));
        } else {
            $nilai = Nilai::findOrFail($id);
            $siswas = Siswa::with('kelas')->get();
            return view('nilai.request_edit', compact('nilai', 'siswas'));
        }
    }

    public function storeRequest(Request $request, $model, $id)
    {
        if (!in_array($model, ['siswa', 'nilai'])) abort(404);

        // Validasi sesuai model
        if ($model === 'siswa') {
            $validated = $request->validate([
                'id' => 'required',
                'nama' => 'required',
                'kelas_id' => 'required',
                'tanggal_lahir' => 'required',
            ]);
        } else {
            $validated = $request->validate([
                'siswa_id' => 'required',
                'sem_1' => 'required',
                'sem_2' => 'required',
                'sem_3' => 'required',
                'sem_4' => 'required',
                'sem_5' => 'required',
                'prestasi' => 'required',
            ]);

            //dd('masuk storeRequest', $request->all(), $model, $id);
        }

        // Simpan permintaan perubahan
        ChangeRequest::create([
            'user_id' => Auth::id(), // ✅ Fix: tambahkan user_id
            'model_type' => ucfirst($model),
            'model_id' => $id,
            'action' => 'edit',
            'data' => $validated,
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard.wakel', ['tab' => $model])
            ->with('success', 'Permintaan perubahan berhasil diajukan.');
    }

}
