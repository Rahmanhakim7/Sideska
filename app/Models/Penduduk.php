<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penduduk extends Model
{
    protected $table = 'penduduk';
    protected $fillable = [
        'user_id',
        'foto',
        'nik',
        'no_kk',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'status_perkawinan',
        'pendidikan',
        'pekerjaan',
        'status_hubungan_keluarga',
        'golongan_darah',
        'alamat',
        'rt',
        'rw',
        'dusun',
        'kewarganegaraan',
        'no_hp',
        'status_kependudukan',
        'disabilitas',
        'nama_ayah',
        'nama_ibu',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pengajuanSurat(): HasMany
    {
        return $this->hasMany(PengajuanSurat::class);
    }
}
