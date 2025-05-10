<?php

namespace App\Exports;

use App\Models\EligibleMipa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EligibleMipaExport implements FromCollection, WithHeadings    
{
    public function collection()
    {
        return EligibleMipa::with('siswa')->get()->map(function ($item) {
            return [
                'NIS' => $item->siswa->id,
                'Nama' => $item->siswa->nama,
                'Kelas' => $item->siswa->kelas->nama_kelas ?? '-',
                'Hasil Akhir' => $item->hasil_akhir,
            ];
        });
    }

    public function headings(): array
    {
        return ['NIS', 'Nama', 'Kelas', 'Hasil Akhir'];
    }
}
