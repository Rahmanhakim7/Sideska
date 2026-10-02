<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanSuratData extends Model
{
    protected $table = 'pengajuan_surat_data';
    protected $fillable = [
        'pengajuan_surat_id',
        'field',
        'value',
    ];
    public function pengajuanSurat(): BelongsTo
    {
        return $this->belongsTo(PengajuanSurat::class);
    }
}
