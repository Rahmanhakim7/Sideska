<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@sideska.test',
            'password' => 'password',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Perangkat Desa',
            'email' => 'perangkat@sideska.test',
            'password' => 'password',
            'role' => 'perangkat_desa',
        ]);

        User::create([
            'name' => 'Kepala Desa',
            'email' => 'kepala@sideska.test',
            'password' => 'password',
            'role' => 'kepala_desa',
        ]);

        User::create([
            'name' => 'Masyarakat',
            'email' => 'masyarakat@sideska.test',
            'password' => 'password',
            'role' => 'masyarakat',
        ]);

        $this->call(PendudukSeeder::class);
        $this->call(PengajuanSuratSeeder::class);
    }
}
