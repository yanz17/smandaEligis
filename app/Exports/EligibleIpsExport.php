<?php

namespace App\Exports;

use App\Models\EligibleIps;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EligibleIpsExport implements FromCollection
{
    public function collection()
    {
        return EligibleIps::with('siswa')->get()->map(function ($item) {
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
