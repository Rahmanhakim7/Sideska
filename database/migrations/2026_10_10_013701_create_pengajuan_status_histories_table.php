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
        Schema::create('pengajuan_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_surat_id')
                ->constrained('pengajuan_surat')
                ->cascadeOnDelete();
            $table->string('status_sebelum')->nullable();
            $table->string('status_sesudah');
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index(['pengajuan_surat_id', 'created_at']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_status_histories');
    }
};
