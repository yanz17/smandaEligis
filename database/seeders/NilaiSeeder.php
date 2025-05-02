<?php

namespace Database\Seeders;

use App\Models\Nilai;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NilaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Nilai::insert([
            [
                'id' => 1,
                'siswa_id' => 2,
                'sem_1' => 89,
                'sem_2' => 89,
                'sem_3' => 89,
                'sem_4' => 89,
                'sem_5' => 89,
                'prestasi' => 89,
            ],
        ]);
    }
}
