<?php

namespace App\Exports;

use App\Models\EligibleMipa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EligibleMipaExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping, 
    WithStyles, 
    ShouldAutoSize, 
    WithEvents
{
    public function collection()
    {
        return EligibleMipa::with('siswa.kelas')->get();
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
        // Header data mulai dari baris ke-6, karena baris 1–5 akan dipakai untuk judul dan info
        return ['NIS', 'Nama', 'Kelas', 'Hasil Akhir'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Judul (baris 1) tebal dan besar
            1 => ['font' => ['bold' => true, 'size' => 16], 'alignment' => ['horizontal' => 'center']],
            2 => ['font' => ['italic' => true], 'alignment' => ['horizontal' => 'center']],
            3 => ['alignment' => ['horizontal' => 'center']],
            5 => ['font' => ['bold' => true]], // Baris heading kolom
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Judul utama
                $sheet->mergeCells('A1:D1');
                $sheet->setCellValue('A1', 'Daftar Eligible SNBP Tahun Ajaran 2024/2025');

                // Subjudul
                $sheet->mergeCells('A2:D2');
                $sheet->setCellValue('A2', 'Jurusan MIPA - SMAN 2 Kuningan');

                // Info tambahan
                $sheet->mergeCells('A3:D3');
                $sheet->setCellValue('A3', 'Tanggal Cetak: ' . now()->format('d F Y'));

                // Kosongkan baris 4 untuk spasi visual
            },
        ];
    }
}
