<?php

namespace Database\Seeders;
use App\Models\Kelas;
use App\Models\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wakel = User::where('role', 'wakel')->first();

        Kelas::insert([
                [
                    'nama_kelas' => 'XII MIPA 1',
                    'jurusan' => 'MIPA',
                    'user_id' => 2,
                ],
                [
                    'nama_kelas' => 'XII MIPA 2',
                    'jurusan' => 'MIPA',
                    'user_id' => 4,
                ],
                [
                    'nama_kelas' => 'XII MIPA 3',
                    'jurusan' => 'MIPA',
                    'user_id' => 5,
                ],
                [
                    'nama_kelas' => 'XII MIPA 4',
                    'jurusan' => 'MIPA',
                    'user_id' => 6,
                ],
                [
                    'nama_kelas' => 'XII MIPA 5',
                    'jurusan' => 'MIPA',
                    'user_id' => 7,
                ],
                [
                    'nama_kelas' => 'XII MIPA 6',
                    'jurusan' => 'MIPA',
                    'user_id' => 8,
                ],
                [
                    'nama_kelas' => 'XII MIPA 7',
                    'jurusan' => 'MIPA',
                    'user_id' => 9,
                ],
                [
                    'nama_kelas' => 'XII IPS 1',
                    'jurusan' => 'IPS',
                    'user_id' => 10,
                ],
                [
                    'nama_kelas' => 'XII IPS 2',
                    'jurusan' => 'IPS',
                    'user_id' => 11,
                ],
                [
                    'nama_kelas' => 'XII IPS 3',
                    'jurusan' => 'IPS',
                    'user_id' => 12,
                ],
            ]);
    }
}
