<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            // Indeks unik parsial: hanya nomor berformat kanonik 470/{urutan}/DS/{tahun}
            // yang dijaga unik, sehingga data lama berformat lain tidak diblokir.
            DB::statement(
                'CREATE UNIQUE INDEX pengajuan_surat_nomor_surat_khusus_unik '
                .'ON pengajuan_surat (nomor_surat) '
                ."WHERE nomor_surat ~ '^470/[0-9]+/DS/[0-9]{4}$'"
            );

            return;
        }

        Schema::table('pengajuan_surat', function (Blueprint $table) {
            $table->unique('nomor_surat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement(
                'DROP INDEX IF EXISTS pengajuan_surat_nomor_surat_khusus_unik'
            );

            return;
        }

        Schema::table('pengajuan_surat', function (Blueprint $table) {
            $table->dropUnique('pengajuan_surat_nomor_surat_unique');
        });
    }
};
