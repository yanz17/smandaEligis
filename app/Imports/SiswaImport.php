<?php
namespace App\Imports;

use App\Models\Siswa;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SiswaImport implements OnEachRow
{
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        // Lewati baris kosong atau header
        if (strtolower($row[0]) === 'id' || $row[0] === null) {
            return;
        }

        $id = $row[0]; // ID siswa
        $nama = $row[1];
        $tanggal = $row[2];
        $kelas_id = $row[3];

        if (!$nama || !$tanggal || !is_numeric($kelas_id)) {
            return; // skip invalid row
        }

        // Format tanggal lahir
        try {
            $tanggal_lahir = is_numeric($tanggal)
                ? Date::excelToDateTimeObject($tanggal)->format('Y-m-d')
                : Carbon::parse($tanggal)->format('Y-m-d');
        } catch (\Exception $e) {
            return;
        }

        if ($id && Siswa::find($id)) {
            // Update data jika ID ada
            Siswa::where('id', $id)->update([
                'id' => $id,
                'nama' => $nama,
                'tanggal_lahir' => $tanggal_lahir,
                'kelas_id' => $kelas_id,
            ]);
        } else {
            // Simpan data baru
            Siswa::create([
                'id' => $id,
                'nama' => $nama,
                'tanggal_lahir' => $tanggal_lahir,
                'kelas_id' => $kelas_id,
            ]);
        }
    }
}
