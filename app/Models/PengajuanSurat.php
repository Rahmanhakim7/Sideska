<?php

namespace App\Models;

use App\Enums\PengajuanSuratStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengajuanSurat extends Model
{
    protected $table = 'pengajuan_surat';

    protected $fillable = [
        'penduduk_id',
        'jenis_surat',
        'keperluan',
        'status',
        'nomor_surat',
        'tanggal_surat',
        'tanggal_selesai',
        'file_pdf',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'status' => PengajuanSuratStatus::class,
            'tanggal_surat' => 'date',
            'tanggal_selesai' => 'datetime',
        ];
    }

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class);
    }

    public function data(): HasMany
    {
        return $this->hasMany(PengajuanSuratData::class);
    }
}
