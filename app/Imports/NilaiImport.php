<?php

namespace App\Imports;

use App\Models\Nilai;
use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;

class NilaiImport implements OnEachRow
{
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        // Lewati header atau baris kosong
        if (strtolower($row[0]) === 'siswa_id' || $row[0] === null) {
            return;
        }

        $siswa_id = $row[0];
        $sem_1 = $row[1] ?? 0;
        $sem_2 = $row[2] ?? 0;
        $sem_3 = $row[3] ?? 0;
        $sem_4 = $row[4] ?? 0;
        $sem_5 = $row[5] ?? 0;
        $prestasi = $row[6] ?? 0;

        // Pastikan siswa ada
        if (!Siswa::find($siswa_id)) {
            return;
        }

        // Cek apakah nilai untuk siswa ini sudah ada
        if (!Nilai::where('siswa_id', $siswa_id)->exists()) {
            Nilai::create([
                'siswa_id' => $siswa_id,
                'sem_1' => $sem_1,
                'sem_2' => $sem_2,
                'sem_3' => $sem_3,
                'sem_4' => $sem_4,
                'sem_5' => $sem_5,
                'prestasi' => $prestasi,
            ]);
        }
    }
}
