<?php

namespace App\Services;

use App\Enums\PengajuanSuratStatus;
use App\Models\PengajuanStatusHistory;
use App\Models\PengajuanSurat;
use App\Models\User;
use App\Notifications\PengajuanStatusNotification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Notification;
use InvalidArgumentException;

class PengajuanStatusService
{
    /**
     * Daftar transisi status yang sah.
     *
     * @var array<string, array<int, string>>
     */
    private const TRANSISI_SAH = [
        'diajukan' => ['diproses', 'revisi', 'ditolak'],
        'revisi' => ['diajukan'],
        'diproses' => ['menunggu_tanda_tangan'],
        'menunggu_tanda_tangan' => ['selesai'],
        'ditolak' => [],
        'selesai' => [],
    ];

    /**
     * Terapkan status baru, catat riwayat, dan persist seluruh atribut
     * kotor pada model (mis. catatan, nomor_surat, file_pdf) dalam satu
     * panggilan save().
     *
     * @throws InvalidArgumentException Jika transisi tidak sah.
     */
    public function apply(
        PengajuanSurat $pengajuan,
        PengajuanSuratStatus $tujuan,
        ?User $pelaku = null
    ): void {
        $asal = $pengajuan->status;

        $this->pastikanTransisiSah($asal, $tujuan);

        $pengajuan->status = $tujuan;
        $pengajuan->save();

        PengajuanStatusHistory::create([
            'pengajuan_surat_id' => $pengajuan->id,
            'status_sebelum' => $asal?->value,
            'status_sesudah' => $tujuan->value,
            'user_id' => $pelaku?->id,
            'catatan' => $pengajuan->catatan,
        ]);
    }

    /**
     * Catat sejarah awal pengajuan baru (null -> status awal, biasanya
     * diajukan). Tidak mengubah status karena sudah diset saat create.
     */
    public function awali(
        PengajuanSurat $pengajuan,
        ?User $pelaku = null
    ): void {
        PengajuanStatusHistory::create([
            'pengajuan_surat_id' => $pengajuan->id,
            'status_sebelum' => null,
            'status_sesudah' => $pengajuan->status?->value
                ?? PengajuanSuratStatus::DIAJUKAN->value,
            'user_id' => $pelaku?->id,
            'catatan' => $pengajuan->catatan,
        ]);
    }

    /**
     * Kirim notifikasi perubahan status. Tidak boleh melempar error:
     * kegagalan notifikasi tidak boleh menggugurkan status tersimpan.
     */
    public function kirimNotifikasi(
        PengajuanSurat $pengajuan,
        ?PengajuanSuratStatus $asal,
        PengajuanSuratStatus $tujuan
    ): void {
        try {
            if (! $pengajuan->relationLoaded('penduduk')) {
                $pengajuan->load('penduduk');
            }

            $sasaran = $this->sasaranNotifikasi($pengajuan, $asal, $tujuan);

            if ($sasaran->isEmpty()) {
                return;
            }

            Notification::send(
                $sasaran,
                new PengajuanStatusNotification(
                    $pengajuan,
                    $this->pesanNotifikasi($pengajuan, $asal, $tujuan),
                    $this->urlNotifikasi($pengajuan, $asal, $tujuan),
                    $tujuan
                )
            );
        } catch (\Throwable $e) {
            report($e);
        }
    }

    private function pastikanTransisiSah(
        ?PengajuanSuratStatus $asal,
        PengajuanSuratStatus $tujuan
    ): void {
        if ($asal === null) {
            throw new InvalidArgumentException(
                'Pengajuan belum memiliki status awal.'
            );
        }

        $boleh = self::TRANSISI_SAH[$asal->value] ?? [];

        if (! in_array($tujuan->value, $boleh, true)) {
            throw new InvalidArgumentException(
                "Transisi status dari {$asal->value} ke {$tujuan->value} tidak sah."
            );
        }
    }

    /**
     * @return Collection<int, User>
     */
    private function sasaranNotifikasi(
        PengajuanSurat $pengajuan,
        ?PengajuanSuratStatus $asal,
        PengajuanSuratStatus $tujuan
    ): Collection {
        $pengajuanBaru = ($asal === null || $asal === PengajuanSuratStatus::REVISI)
            && $tujuan === PengajuanSuratStatus::DIAJUKAN;

        if ($pengajuanBaru) {
            return User::query()
                ->where('role', 'perangkat_desa')
                ->get();
        }

        if (
            $asal === PengajuanSuratStatus::DIPROSES
            && $tujuan === PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN
        ) {
            return User::query()
                ->where('role', 'kepala_desa')
                ->get();
        }

        $pemohon = $this->pemohon($pengajuan);

        if ($pemohon === null) {
            return new Collection;
        }

        return new Collection([$pemohon]);
    }

    private function pemohon(PengajuanSurat $pengajuan): ?User
    {
        return User::query()
            ->whereHas('penduduk', function ($query) use ($pengajuan) {
                $query->whereKey($pengajuan->penduduk_id);
            })
            ->first();
    }

    private function pesanNotifikasi(
        PengajuanSurat $pengajuan,
        ?PengajuanSuratStatus $asal,
        PengajuanSuratStatus $tujuan
    ): string {
        $jenis = ucwords(str_replace('_', ' ', $pengajuan->jenis_surat));
        $pemohon = $pengajuan->penduduk->nama ?? 'warga';

        return match ($tujuan) {
            PengajuanSuratStatus::DIAJUKAN => $asal === null
                ? "Pengajuan surat {$jenis} baru dari {$pemohon}."
                : "{$pemohon} telah mengirim kembali perbaikan pengajuan surat {$jenis}.",
            PengajuanSuratStatus::REVISI => "Pengajuan surat {$jenis} Anda perlu diperbaiki. Periksa catatan perangkat desa.",
            PengajuanSuratStatus::DITOLAK => "Pengajuan surat {$jenis} Anda ditolak. Periksa catatan perangkat desa.",
            PengajuanSuratStatus::DIPROSES => "Pengajuan surat {$jenis} Anda sedang diproses perangkat desa.",
            PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN => "Surat {$jenis} menunggu tanda tangan Kepala Desa.",
            PengajuanSuratStatus::SELESAI => "Surat {$jenis} Anda telah selesai dibuat. Silakan unduh surat.",
        };
    }

    private function urlNotifikasi(
        PengajuanSurat $pengajuan,
        ?PengajuanSuratStatus $asal,
        PengajuanSuratStatus $tujuan
    ): string {
        if ($tujuan === PengajuanSuratStatus::DIAJUKAN) {
            return route('perangkat-desa.pelayanan-surat.detail', [
                'pengajuan' => $pengajuan->id,
            ]);
        }

        if (
            $asal === PengajuanSuratStatus::DIAJUKAN
            && $tujuan === PengajuanSuratStatus::REVISI
        ) {
            return $this->urlPerbaikan($pengajuan);
        }

        if (
            $asal === PengajuanSuratStatus::DIPROSES
            && $tujuan === PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN
        ) {
            return route('kepala.persetujuan.detail', [
                'pengajuan' => $pengajuan->id,
            ]);
        }

        return route('masyarakat.surat.detail', [
            'pengajuan' => $pengajuan->id,
        ]);
    }

    private function urlPerbaikan(PengajuanSurat $pengajuan): string
    {
        return match ($pengajuan->jenis_surat) {
            'domisili' => route('masyarakat.ajukan-surat.domisili', [
                'pengajuan' => $pengajuan->id,
            ]),
            'sktm' => route('masyarakat.ajukan-surat.sktm', [
                'pengajuan' => $pengajuan->id,
            ]),
            'usaha' => route('masyarakat.ajukan-surat.usaha', [
                'pengajuan' => $pengajuan->id,
            ]),
            'skck' => route('masyarakat.ajukan-surat.skck', [
                'pengajuan' => $pengajuan->id,
            ]),
            'keterangan_pindah' => route('masyarakat.ajukan-surat.keterangan-pindah', [
                'pengajuan' => $pengajuan->id,
            ]),
            default => route('notifikasi'),
        };
    }
}
