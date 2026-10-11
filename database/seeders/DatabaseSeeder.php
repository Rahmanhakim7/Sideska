<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@sideska.test',
            'password' => 'password',
        ]);
        $admin->role = 'admin';
        $admin->save();

        $perangkat = User::create([
            'name' => 'Perangkat Desa',
            'email' => 'perangkat@sideska.test',
            'password' => 'password',
        ]);
        $perangkat->role = 'perangkat_desa';
        $perangkat->save();

        $kepala = User::create([
            'name' => 'Kepala Desa',
            'email' => 'kepala@sideska.test',
            'password' => 'password',
        ]);
        $kepala->role = 'kepala_desa';
        $kepala->save();

        $masyarakat = User::create([
            'name' => 'Masyarakat',
            'email' => 'masyarakat@sideska.test',
            'password' => 'password',
        ]);
        $masyarakat->role = 'masyarakat';
        $masyarakat->save();

        $this->call(PendudukSeeder::class);
        $this->call(PengajuanSuratSeeder::class);
    }
}
