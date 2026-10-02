<?php

namespace Database\Seeders;

use App\Enums\PengajuanSuratStatus;
use App\Models\Penduduk;
use App\Models\PengajuanSurat;
use Illuminate\Database\Seeder;

class PengajuanSuratSeeder extends Seeder
{
    public function run(): void
    {
        $penduduk = Penduduk::firstOrFail();

        $data = [
            [
                'penduduk_id' => $penduduk->id,
                'jenis_surat' => 'sktm',
                'keperluan' => 'Untuk keperluan administrasi sekolah.',
                'status' => PengajuanSuratStatus::DIAJUKAN,
                'tanggal_surat' => null,
                'nomor_surat' => null,
                'tanggal_selesai' => null,
            ],

            [
                'penduduk_id' => $penduduk->id,
                'jenis_surat' => 'sktm',
                'keperluan' => 'Untuk keperluan administrasi pekerjaan.',
                'status' => PengajuanSuratStatus::DIAJUKAN,
                'tanggal_surat' => now()->subDays(5)->toDateString(),
                'nomor_surat' => '470/001/DS/2026',
                'tanggal_selesai' => now()->subDays(5),
            ],

            [
                'penduduk_id' => $penduduk->id,
                'jenis_surat' => 'sktm',
                'keperluan' => 'Untuk keperluan pendaftaran rekening bank.',
                'status' => PengajuanSuratStatus::DIAJUKAN,
                'tanggal_surat' => now()->subDays(4)->toDateString(),
                'nomor_surat' => '470/002/DS/2026',
                'tanggal_selesai' => now()->subDays(4),
            ],

            [
                'penduduk_id' => $penduduk->id,
                'jenis_surat' => 'domisili',
                'keperluan' => 'Untuk keperluan administrasi kependudukan.',
                'status' => PengajuanSuratStatus::DIAJUKAN,
                'tanggal_surat' => now()->subDays(3)->toDateString(),
                'nomor_surat' => '470/003/DS/2026',
                'tanggal_selesai' => now()->subDays(3),
            ],

            [
                'penduduk_id' => $penduduk->id,
                'jenis_surat' => 'domisili',
                'keperluan' => 'Untuk keperluan pengajuan bantuan.',
                'status' => PengajuanSuratStatus::DIAJUKAN,
                'tanggal_surat' => null,
                'nomor_surat' => null,
                'tanggal_selesai' => null,
            ],

            [
                'penduduk_id' => $penduduk->id,
                'jenis_surat' => 'domisili',
                'keperluan' => 'Untuk keperluan administrasi pernikahan.',
                'status' => PengajuanSuratStatus::DIAJUKAN,
                'tanggal_surat' => null,
                'nomor_surat' => null,
                'tanggal_selesai' => null,
            ],

            [
                'penduduk_id' => $penduduk->id,
                'jenis_surat' => 'domisili',
                'keperluan' => 'Untuk keperluan pendaftaran kuliah.',
                'status' => PengajuanSuratStatus::DIAJUKAN,
                'tanggal_surat' => null,
                'nomor_surat' => null,
                'tanggal_selesai' => null,
            ],

            [
                'penduduk_id' => $penduduk->id,
                'jenis_surat' => 'domisili',
                'keperluan' => 'Untuk keperluan administrasi perusahaan.',
                'status' => PengajuanSuratStatus::DIAJUKAN,
                'tanggal_surat' => now()->subDays(2)->toDateString(),
                'nomor_surat' => '470/004/DS/2026',
                'tanggal_selesai' => now()->subDays(2),
            ],

            [
                'penduduk_id' => $penduduk->id,
                'jenis_surat' => 'domisili',
                'keperluan' => 'Untuk keperluan pengurusan dokumen resmi.',
                'status' => PengajuanSuratStatus::DIAJUKAN,
                'tanggal_surat' => now()->subDays(7)->toDateString(),
                'nomor_surat' => '470/005/DS/2026',
                'tanggal_selesai' => now()->subDays(7),
            ],

            [
                'penduduk_id' => $penduduk->id,
                'jenis_surat' => 'domisili',
                'keperluan' => 'Untuk keperluan administrasi BPJS.',
                'status' => PengajuanSuratStatus::DIAJUKAN,
                'tanggal_surat' => now()->subDays(8)->toDateString(),
                'nomor_surat' => '470/006/DS/2026',
                'tanggal_selesai' => now()->subDays(8),
            ],

            [
                'penduduk_id' => $penduduk->id,
                'jenis_surat' => 'domisili',
                'keperluan' => 'Untuk keperluan administrasi tempat tinggal.',
                'status' => PengajuanSuratStatus::DIAJUKAN,
                'tanggal_surat' => null,
                'nomor_surat' => null,
                'tanggal_selesai' => null,
            ],

            [
                'penduduk_id' => $penduduk->id,
                'jenis_surat' => 'domisili',
                'keperluan' => 'Untuk keperluan pengajuan kredit.',
                'status' => PengajuanSuratStatus::DIAJUKAN,
                'tanggal_surat' => now()->subDays(10)->toDateString(),
                'nomor_surat' => '470/007/DS/2026',
                'tanggal_selesai' => now()->subDays(10),
            ],

            [
                'penduduk_id' => $penduduk->id,
                'jenis_surat' => 'domisili',
                'keperluan' => 'Untuk keperluan administrasi keluarga.',
                'status' => PengajuanSuratStatus::DIAJUKAN,
                'tanggal_surat' => null,
                'nomor_surat' => null,
                'tanggal_selesai' => null,
            ],

            [
                'penduduk_id' => $penduduk->id,
                'jenis_surat' => 'domisili',
                'keperluan' => 'Untuk keperluan pendaftaran kerja.',
                'status' => PengajuanSuratStatus::DIAJUKAN,
                'tanggal_surat' => null,
                'nomor_surat' => null,
                'tanggal_selesai' => null,
            ],

            [
                'penduduk_id' => $penduduk->id,
                'jenis_surat' => 'domisili',
                'keperluan' => 'Untuk keperluan administrasi lainnya.',
                'status' => PengajuanSuratStatus::DIAJUKAN,
                'tanggal_surat' => null,
                'nomor_surat' => null,
                'tanggal_selesai' => null,
            ],
        ];

        foreach ($data as $item) {
            PengajuanSurat::create($item);
        }
    }
}
