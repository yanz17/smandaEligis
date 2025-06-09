<?php

namespace App\Imports;

use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Row;
use Throwable;

class NilaiImport implements OnEachRow, SkipsOnFailure
{
    use SkipsFailures;

    public static $errors = [];
    protected $allowedKelasId = null;

    public function __construct($allowedKelasId = null)
    {
        $this->allowedKelasId = $allowedKelasId;
    }

    public function onRow(Row $row)
    {
        try {
            $rowIndex = $row->getIndex();
            $row = $row->toArray();

            if (empty($row[0]) || strtolower($row[0]) === 'siswa_id') {
                return;
            }

            $siswa_id = $row[0];
            $semesters = array_slice($row, 1, 5);
            $prestasi = $row[6] ?? 0;

            // Validasi siswa
            $siswa = Siswa::find($siswa_id);
            if (!$siswa) {
                self::$errors[] = "Baris $rowIndex: Siswa dengan ID $siswa_id tidak ditemukan.";
                return;
            }

            // ❗ Batasan kelas untuk wakel
            if ($this->allowedKelasId && $siswa->kelas_id != $this->allowedKelasId) {
                self::$errors[] = "Baris $rowIndex: Siswa ID $siswa_id bukan dari kelas Anda.";
                return;
            }

            // Cek duplikasi
            if (Nilai::where('siswa_id', $siswa_id)->exists()) {
                self::$errors[] = "Baris $rowIndex: Nilai untuk siswa ini sudah ada.";
                return;
            }

            // Validasi nilai numerik
            foreach ($semesters as $index => $val) {
                if (!is_numeric($val) && !is_null($val)) {
                    self::$errors[] = "Baris $rowIndex: Semester " . ($index + 1) . " harus berupa angka.";
                    return;
                }
            }

            if (!is_numeric($prestasi) && !is_null($prestasi)) {
                self::$errors[] = "Baris $rowIndex: Prestasi harus berupa angka.";
                return;
            }

            // Simpan nilai
            Nilai::create([
                'siswa_id' => $siswa_id,
                'sem_1' => round($semesters[0] ?? 0, 2),
                'sem_2' => round($semesters[1] ?? 0, 2),
                'sem_3' => round($semesters[2] ?? 0, 2),
                'sem_4' => round($semesters[3] ?? 0, 2),
                'sem_5' => round($semesters[4] ?? 0, 2),
                'prestasi' => round($prestasi ?? 0, 2),
            ]);

        } catch (Throwable $e) {
            self::$errors[] = "Baris {$row->getIndex()}: Terjadi kesalahan - " . $e->getMessage();
        }
    }
}

