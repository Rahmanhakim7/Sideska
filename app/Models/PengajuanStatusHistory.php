<?php

namespace App\Models;

use App\Enums\PengajuanSuratStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanStatusHistory extends Model
{
    protected $table = 'pengajuan_status_histories';

    protected $fillable = [
        'pengajuan_surat_id',
        'status_sebelum',
        'status_sesudah',
        'user_id',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'status_sebelum' => PengajuanSuratStatus::class,
            'status_sesudah' => PengajuanSuratStatus::class,
        ];
    }

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(PengajuanSurat::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
