<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Penduduk extends Model
{
    use HasFactory;

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

    public function fotoUrl(): Attribute
    {
        return Attribute::make(
            get: function (): ?string {
                $foto = $this->foto;

                if ($foto === null || $foto === '') {
                    return null;
                }

                $skema = parse_url($foto, PHP_URL_SCHEME);

                if ($skema !== null) {
                    return in_array($skema, ['http', 'https'], true) ? $foto : null;
                }

                return Storage::disk('public')->url($foto);
            }
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pengajuanSurat(): HasMany
    {
        return $this->hasMany(PengajuanSurat::class);
    }

    public function mutasiPenduduk(): HasMany
    {
        return $this->hasMany(MutasiPenduduk::class);
    }
}
