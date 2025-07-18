<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Validation\ValidationException;

class SiswaImport implements ToCollection
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function collection(Collection $rows)
    {
        $kelasWakel = [];

        if ($this->user->role === 'wakel') {
            $kelasWakel = Kelas::where('user_id', $this->user->id)->pluck('id')->toArray();
        }

        $errors = [];

        foreach ($rows as $index => $row) {
            // Lewati header jika ada
            if ($index === 0 && $row[0] === 'id') continue;

            // Cek jumlah kolom minimal
            if (count($row) < 4) {
                $errors[] = "Baris " . ($index + 1) . ": Kolom tidak lengkap.";
                continue;
            }

            $id = $row[0];
            $nama = $row[1];
            $tanggal_lahir = $row[2];
            $kelasId = $row[3];

            // Validasi isi kolom
            if (!is_numeric($id) || strlen((string)$id) < 10) {
                $errors[] = "Baris " . ($index + 1) . ": ID siswa tidak valid.";
            }

            if (!is_string($nama) || strlen($nama) < 3) {
                $errors[] = "Baris " . ($index + 1) . ": Nama tidak valid.";
            }

            try {
                if (is_numeric($tanggal_lahir)) {
                    $tanggal = Date::excelToDateTimeObject($tanggal_lahir);
                } else {
                    $tanggal = \Carbon\Carbon::parse($tanggal_lahir);
                }
            } catch (\Exception $e) {
                $errors[] = "Baris " . ($index + 1) . ": Tanggal lahir tidak valid.";
            }

            // Validasi kelas untuk wakel
            if ($this->user->role === 'wakel' && !in_array($kelasId, $kelasWakel)) {
                $errors[] = "Baris " . ($index + 1) . ": Kelas tidak sesuai dengan kelas Anda.";
            }
        }

        if (count($errors)) {
            throw new \Exception("Import gagal:\n" . implode("\n", $errors));
        }

        // Simpan jika semua valid
        foreach ($rows as $index => $row) {
            if ($index === 0 && $row[0] === 'id') continue;

            Siswa::create([
                'id' => $row[0],
                'nama' => $row[1],
                'tanggal_lahir' => $tanggal,
                'kelas_id' => $row[3],
            ]);
        }
    }
}
