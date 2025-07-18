<?php

namespace App\Exports;

use App\Models\EligibleIps;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EligibleIpsExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping, 
    WithStyles, 
    ShouldAutoSize, 
    WithEvents
{
    public function collection()
    {
        return EligibleIps::with('siswa.kelas')->get();
    }

    public function map($item): array
    {
        return [
            $item->siswa->id,
            $item->siswa->nama,
            $item->siswa->kelas->nama_kelas ?? '-',
            number_format($item->hasil_akhir, 4),
        ];
    }

    public function headings(): array
    {
        return ['NIS', 'Nama', 'Kelas', 'Hasil Akhir'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 16], 'alignment' => ['horizontal' => 'center']],
            2 => ['font' => ['italic' => true], 'alignment' => ['horizontal' => 'center']],
            3 => ['alignment' => ['horizontal' => 'center']],
            5 => ['font' => ['bold' => true]], // Header baris
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->mergeCells('A1:D1');
                $sheet->setCellValue('A1', 'Daftar Eligible SNBP Tahun Ajaran 2024/2025');

                $sheet->mergeCells('A2:D2');
                $sheet->setCellValue('A2', 'Jurusan IPS - SMAN 2 Kuningan');

                $sheet->mergeCells('A3:D3');
                $sheet->setCellValue('A3', 'Tanggal Cetak: ' . now()->format('d F Y'));
            },
        ];
    }
}
