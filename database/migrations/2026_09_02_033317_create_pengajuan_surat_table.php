<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_surat', function (Blueprint $table) {
            $table->id();

            $table->foreignId('penduduk_id')
                ->constrained('penduduk')
                ->cascadeOnDelete();

            $table->string('jenis_surat');

            $table->text('keperluan');

            $table->string('status')
                ->default('diajukan');

            $table->string('nomor_surat')
                ->nullable();

            $table->date('tanggal_surat')
                ->nullable();

            $table->timestamp('tanggal_selesai')
                ->nullable();

            $table->string('file_pdf')
                ->nullable();

            $table->text('catatan')
                ->nullable();

            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surat');
    }
};
