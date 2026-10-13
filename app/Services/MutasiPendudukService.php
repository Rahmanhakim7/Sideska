<?php

namespace App\Services;

use App\Enums\JenisMutasi;
use App\Enums\StatusKependudukan;
use App\Models\MutasiPenduduk;
use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MutasiPendudukService
{
    /**
     * Wilayah administratif desa, dipakai sebagai bagian dari snapshot alamat.
     */
    public const WILAYAH = 'Desa Sekartejo, Kecamatan Pituruh, Kabupaten Purworejo';

    /**
     * Susun snapshot alamat lengkap dari data penduduk.
     */
    public function alamatSnapshot(Penduduk $penduduk): string
    {
        $bagian = array_filter([
            $penduduk->alamat,
            'RT '.$penduduk->rt.' RW '.$penduduk->rw,
            'Dusun '.$penduduk->dusun,
            self::WILAYAH,
        ], fn (?string $nilai): bool => filled($nilai));

        return implode(', ', $bagian);
    }

    /**
     * Jalankan workflow Mutasi Masuk secara atomik.
     *
     * Membuat User baru (role masyarakat, aktif), Penduduk baru berstatus
     * Pendatang, dan MutasiPenduduk berjenis MASUK di dalam satu transaksi.
     * Jika salah satu langkah gagal, seluruh transaksi di-rollback sehingga
     * tidak ada User, Penduduk, atau Mutasi yang tersisa sebagian.
     *
     * Actor selalu diambil dari Auth::user(), bukan dari payload.
     *
     * @param  array<string, mixed>  $data
     */
    public function mutasiMasuk(array $data): MutasiPenduduk
    {
        $aktor = Auth::user();

        return DB::transaction(function () use ($data, $aktor): MutasiPenduduk {
            $user = User::create([
                'name' => $data['nama'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            $user->role = 'masyarakat';
            $user->is_active = true;
            $user->save();

            $penduduk = Penduduk::create([
                'user_id' => $user->id,
                'nik' => $data['nik'],
                'no_kk' => $data['no_kk'],
                'nama' => $data['nama'],
                'tempat_lahir' => $data['tempat_lahir'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'agama' => $data['agama'],
                'status_perkawinan' => $data['status_perkawinan'],
                'pendidikan' => $data['pendidikan'],
                'pekerjaan' => $data['pekerjaan'],
                'status_hubungan_keluarga' => $data['status_hubungan_keluarga'],
                'golongan_darah' => $data['golongan_darah'] ?? null,
                'alamat' => $data['alamat'],
                'rt' => $data['rt'],
                'rw' => $data['rw'],
                'dusun' => $data['dusun'],
                'kewarganegaraan' => $data['kewarganegaraan'],
                'no_hp' => $data['no_hp'] ?? null,
                'status_kependudukan' => StatusKependudukan::PENDATANG->value,
                'disabilitas' => $data['disabilitas'],
                'nama_ayah' => $data['nama_ayah'] ?? null,
                'nama_ibu' => $data['nama_ibu'] ?? null,
            ]);

            return MutasiPenduduk::create([
                'penduduk_id' => $penduduk->id,
                'jenis' => JenisMutasi::MASUK,
                'tanggal_mutasi' => $data['tanggal_mutasi'],
                'alamat_asal' => $data['alamat_asal'] ?? null,
                'alamat_tujuan' => $this->alamatSnapshot($penduduk),
                'alasan' => $data['alasan'] ?? null,
                'catatan' => $data['catatan'] ?? null,
                'pengajuan_surat_id' => $data['pengajuan_surat_id'] ?? null,
                'user_id' => $aktor?->id,
            ]);
        });
    }
}
