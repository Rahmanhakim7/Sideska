<?php

namespace App\Models;

use App\Enums\JenisMutasi;
use Database\Factories\MutasiPendudukFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MutasiPenduduk extends Model
{
    /** @use HasFactory<MutasiPendudukFactory> */
    use HasFactory;

    protected $fillable = [
        'penduduk_id',
        'jenis',
        'tanggal_mutasi',
        'alamat_asal',
        'alamat_tujuan',
        'alasan',
        'catatan',
        'pengajuan_surat_id',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'jenis' => JenisMutasi::class,
            'tanggal_mutasi' => 'date',
        ];
    }

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class);
    }

    public function pengajuanSurat(): BelongsTo
    {
        return $this->belongsTo(PengajuanSurat::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
