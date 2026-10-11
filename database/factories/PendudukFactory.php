<?php

namespace Database\Factories;

use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Penduduk>
 */
class PendudukFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nik' => fake()->numerify('################'),
            'no_kk' => fake()->numerify('################'),
            'nama' => fake()->name(),
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => fake()->date(),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'agama' => fake()->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']),
            'status_perkawinan' => fake()->randomElement(['Belum Kawin', 'Kawin']),
            'pendidikan' => fake()->randomElement(['SD', 'SMP', 'SMA', 'S1', 'S2']),
            'pekerjaan' => fake()->jobTitle(),
            'status_hubungan_keluarga' => 'Kepala Keluarga',
            'golongan_darah' => fake()->randomElement(['O', 'A', 'B', 'AB']),
            'alamat' => fake()->streetAddress(),
            'rt' => (string) fake()->numberBetween(1, 20),
            'rw' => (string) fake()->numberBetween(1, 20),
            'dusun' => 'Dusun '.fake()->word(),
            'kewarganegaraan' => 'WNI',
            'no_hp' => fake()->numerify('08##########'),
            'status_kependudukan' => 'Tetap',
            'disabilitas' => 'Tidak',
            'nama_ayah' => fake()->name('male'),
            'nama_ibu' => fake()->name('female'),
        ];
    }
}
