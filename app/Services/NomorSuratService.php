<?php

namespace App\Services;

use App\Models\PengajuanSurat;
use Illuminate\Support\Facades\DB;

class NomorSuratService
{
    public const KODE_KLASIFIKASI = '470';

    public const KODE_DESA = 'DS';

    public function format(int $urutan, int $tahun): string
    {
        return sprintf(
            '%s/%03d/%s/%d',
            self::KODE_KLASIFIKASI,
            $urutan,
            self::KODE_DESA,
            $tahun
        );
    }

    /**
     * Hitung nomor berikutnya dan aman terhadap dua permintaan bersamaan.
     *
     * Pemanggil wajib menyimpan nomor yang dikembalikan dalam transaksi yang
     * sama (transaksi ini atau transaksi luar yang membungkusnya), sehingga
     * kunci transaksi (advisory lock) PostgreSQL tetap dipegang sampai nomor
     * benar-benar tersimpan.
     */
    public function generate(int $tahun): string
    {
        return DB::transaction(function () use ($tahun) {
            $this->kunciJalur($tahun);

            return $this->format($this->urutanBerikutnya($tahun), $tahun);
        });
    }

    /**
     * Hitung nomor untuk pratinjau tanpa mengunci jalur penomoran.
     *
     * Hasil hanya bersifat indikatif; nomor final yang tersimpan wajib
     * menggunakan generate() agar aman dari permintaan bersamaan.
     */
    public function pratinjau(int $tahun): string
    {
        return $this->format($this->urutanBerikutnya($tahun), $tahun);
    }

    protected function kunciJalur(int $tahun): void
    {
        if (DB::connection()->getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement(
            'SELECT pg_advisory_xact_lock(hashtext(?))',
            [
                'sideska:nomor-surat:'
                .self::KODE_KLASIFIKASI
                .':'
                .self::KODE_DESA
                .':'
                .$tahun,
            ]
        );
    }

    protected function urutanBerikutnya(int $tahun): int
    {
        $nomorTerpakai = PengajuanSurat::query()
            ->whereNotNull('nomor_surat')
            ->orderBy('id')
            ->pluck('nomor_surat');

        $urutan = 1;

        foreach ($nomorTerpakai as $nomor) {
            if (preg_match(
                '/^'
                .preg_quote(self::KODE_KLASIFIKASI, '/')
                .'\/(\d+)\/'
                .preg_quote(self::KODE_DESA, '/')
                .'\/'
                .$tahun
                .'$/',
                $nomor,
                $matches
            )) {
                $urutan = max($urutan, (int) $matches[1] + 1);
            }
        }

        return $urutan;
    }
}
