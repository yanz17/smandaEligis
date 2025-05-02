<?php

namespace Database\Seeders;
use App\Models\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'username' => 'gurubk1',
                'password' => 'password', // ⚠️ Gunakan Hash::make di real project
                'role' => 'gurubk',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'wakel1',
                'password' => 'password',
                'role' => 'wakel',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'kepsek1',
                'password' => 'password',
                'role' => 'kepsek',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'wakel2',
                'password' => 'password',
                'role' => 'wakel',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'wakel3',
                'password' => 'password',
                'role' => 'wakel',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'wakel4',
                'password' => 'password',
                'role' => 'wakel',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'wakel5',
                'password' => 'password',
                'role' => 'wakel',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'wakel6',
                'password' => 'password',
                'role' => 'wakel',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'wakel7',
                'password' => 'password',
                'role' => 'wakel',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'wakel8',
                'password' => 'password',
                'role' => 'wakel',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'wakel9',
                'password' => 'password',
                'role' => 'wakel',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'wakel10',
                'password' => 'password',
                'role' => 'wakel',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
