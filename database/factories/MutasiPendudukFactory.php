<?php

namespace Database\Factories;

use App\Enums\JenisMutasi;
use App\Models\MutasiPenduduk;
use App\Models\Penduduk;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MutasiPenduduk>
 */
class MutasiPendudukFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'penduduk_id' => Penduduk::factory(),
            'jenis' => JenisMutasi::KELUAR->value,
            'tanggal_mutasi' => fake()->date(),
            'alamat_asal' => fake()->address(),
            'alamat_tujuan' => null,
            'alasan' => null,
            'catatan' => null,
            'pengajuan_surat_id' => null,
            'user_id' => null,
        ];
    }

    public function masuk(): static
    {
        return $this->state(fn (): array => [
            'jenis' => JenisMutasi::MASUK->value,
            'alamat_asal' => null,
            'alamat_tujuan' => fake()->address(),
        ]);
    }

    public function keluar(): static
    {
        return $this->state(fn (): array => [
            'jenis' => JenisMutasi::KELUAR->value,
            'alamat_asal' => fake()->address(),
            'alamat_tujuan' => null,
        ]);
    }
}
