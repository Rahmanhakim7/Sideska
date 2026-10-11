<?php

namespace Database\Factories;

use App\Enums\PengajuanSuratStatus;
use App\Models\Penduduk;
use App\Models\PengajuanSurat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PengajuanSurat>
 */
class PengajuanSuratFactory extends Factory
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
            'jenis_surat' => fake()->randomElement(['domisili', 'sktm', 'usaha']),
            'keperluan' => fake()->sentence(6),
            'status' => PengajuanSuratStatus::DIAJUKAN->value,
        ];
    }

    public function jenis(string $jenisSurat): static
    {
        return $this->state(fn (): array => ['jenis_surat' => $jenisSurat]);
    }

    public function berstatus(PengajuanSuratStatus $status): static
    {
        return $this->state(fn (): array => ['status' => $status->value]);
    }
}
