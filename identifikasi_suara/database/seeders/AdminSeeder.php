<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('admins')->updateOrInsert(
            ['email' => 'admin@example.com'],
            [
                'nama' => 'admin',
                'password' => Hash::make('Admin123'),
                'no_hp' => '08123456789',
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
