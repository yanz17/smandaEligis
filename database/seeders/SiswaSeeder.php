<?php

namespace Database\Seeders;
use App\Models\Siswa;
use App\Models\Kelas;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelas = Kelas::first();

        Siswa::insert([
            [   
                'id' => 1234567890,
                'nama' => 'Aldi Ramadhan',
                'kelas_id' => $kelas->id,
                'tanggal_lahir' => '2007/12/01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'=> 9876543211,
                'nama' => 'Dina Puspita',
                'kelas_id' => $kelas->id,
                'tanggal_lahir' => '2007/01/12',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
