<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penduduk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('foto')
                ->nullable();
            $table->string('nik', 16)
                ->unique();
            $table->string('no_kk', 16);
            $table->string('nama');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('agama');
            $table->string('status_perkawinan');
            $table->string('pendidikan');
            $table->string('pekerjaan');
            $table->string('status_hubungan_keluarga');
            $table->string('golongan_darah')
                ->nullable();
            $table->text('alamat');
            $table->string('rt', 3);
            $table->string('rw', 3);
            $table->string('dusun');
            $table->string('kewarganegaraan');
            $table->string('no_hp')
                ->nullable();
            $table->string('status_kependudukan');
            $table->string('disabilitas');
            $table->string('nama_ayah')
                ->nullable();
            $table->string('nama_ibu')
                ->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penduduk');
    }
};
