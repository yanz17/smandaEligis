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
                'nama' => 'Aldi Ramadhan',
                'kelas_id' => $kelas->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Dina Puspita',
                'kelas_id' => $kelas->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
