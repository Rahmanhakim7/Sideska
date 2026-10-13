<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mutasi_penduduks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('penduduk_id')
                ->constrained('penduduk')
                ->cascadeOnDelete();

            $table->string('jenis');

            $table->date('tanggal_mutasi');

            $table->text('alamat_asal')
                ->nullable();

            $table->text('alamat_tujuan')
                ->nullable();

            $table->string('alasan')
                ->nullable();

            $table->text('catatan')
                ->nullable();

            $table->foreignId('pengajuan_surat_id')
                ->nullable()
                ->constrained('pengajuan_surat')
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['penduduk_id', 'tanggal_mutasi']);
            $table->index('jenis');
            $table->index('tanggal_mutasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_penduduks');
    }
};
