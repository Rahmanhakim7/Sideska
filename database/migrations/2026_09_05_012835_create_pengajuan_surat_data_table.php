<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_surat_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_surat_id')
                ->constrained('pengajuan_surat')
                ->cascadeOnDelete();
            $table->string('field');
            $table->text('value')
                ->nullable();
            $table->timestamps();
            $table->unique([
                'pengajuan_surat_id',
                'field',
            ]);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surat_data');
    }
};
