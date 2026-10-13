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
use Illuminate\Validation\ValidationException;

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
                'user_id' => $aktor->id,
            ]);
        });
    }

    /**
     * Jalankan workflow Mutasi Keluar secara atomik.
     *
     * Mengunci Penduduk target, memvalidasi ulang aturan bisnis, lalu menandai
     * Penduduk sebagai Pindah dan menonaktifkan User terkait, lalu mencatat
     * MutasiPenduduk berjenis KELUAR. Tidak ada Penduduk, User, atau PengajuanSurat
     * yang dihapus — mutasi hanya sebuah event riwayat.
     *
     * Actor selalu diambil dari Auth::user(), bukan dari payload.
     *
     * @param  array<string, mixed>  $data
     *
     * @throws ValidationException
     */
    public function mutasiKeluar(int $pendudukId, array $data): MutasiPenduduk
    {
        $aktor = Auth::user();

        return DB::transaction(function () use ($pendudukId, $data, $aktor): MutasiPenduduk {
            $penduduk = Penduduk::query()
                ->with('user')
                ->lockForUpdate()
                ->findOrFail($pendudukId);

            $this->pastikanDapatMutasiKeluar($penduduk);

            $alamatAsal = $this->alamatSnapshot($penduduk);

            $penduduk->update([
                'status_kependudukan' => StatusKependudukan::PINDAH->value,
            ]);

            $user = $penduduk->user;
            $user->is_active = false;
            $user->save();

            return MutasiPenduduk::create([
                'penduduk_id' => $penduduk->id,
                'jenis' => JenisMutasi::KELUAR,
                'tanggal_mutasi' => $data['tanggal_mutasi'],
                'alamat_asal' => $alamatAsal,
                'alamat_tujuan' => $data['alamat_tujuan'],
                'alasan' => $data['alasan'] ?? null,
                'catatan' => $data['catatan'] ?? null,
                'pengajuan_surat_id' => $data['pengajuan_surat_id'] ?? null,
                'user_id' => $aktor?->id,
            ]);
        });
    }

    /**
     * Validasi ulang aturan bisnis Mutasi Keluar setelah Penduduk terkunci.
     *
     * Dilakukan di dalam transaksi, setelah lockForUpdate(), agar tidak hanya
     * bergantung pada validation di sisi Livewire.
     *
     * @throws ValidationException
     */
    private function pastikanDapatMutasiKeluar(Penduduk $penduduk): void
    {
        if ($penduduk->user === null) {
            throw ValidationException::withMessages([
                'penduduk' => 'Penduduk ini tidak memiliki akun pengguna sehingga tidak dapat dimutasi keluar.',
            ]);
        }

        if ($penduduk->status_kependudukan === StatusKependudukan::PINDAH->value) {
            throw ValidationException::withMessages([
                'penduduk' => 'Penduduk ini sudah berstatus Pindah dan tidak dapat dimutasi keluar lagi.',
            ]);
        }
    }
}
