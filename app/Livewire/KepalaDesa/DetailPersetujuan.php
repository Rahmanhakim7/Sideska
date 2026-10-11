<?php

namespace App\Livewire\KepalaDesa;

use App\Enums\PengajuanSuratStatus;
use App\Models\PengajuanSurat;
use App\Services\PengajuanStatusService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class DetailPersetujuan extends Component
{
    public PengajuanSurat $pengajuan;

    public bool $showModalTandaTangan = false;

    public function mount(PengajuanSurat $pengajuan): void
    {
        abort_unless(
            in_array($pengajuan->status, [
                PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN,
                PengajuanSuratStatus::SELESAI,
            ], true),
            404
        );

        $this->pengajuan = $pengajuan->load([
            'penduduk',
            'statusHistories.user',
        ]);
    }

    public function tandaTangani(): void
    {
        if (
            $this->pengajuan->status !==
            PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN
        ) {
            return;
        }
        $this->pengajuan->load(['penduduk', 'data']);
        $oldFilePath = $this->pengajuan->file_pdf;
        $template = match ($this->pengajuan->jenis_surat) {
            'domisili' => 'surat.domisili',
            'sktm' => 'surat.sktm',
            'usaha' => 'surat.usaha',
            'skck' => 'surat.skck',
            default => null,
        };
        abort_unless($template, 404);
        $dataSktm = $this->pengajuan
            ->data
            ->pluck('value', 'field');
        $filePath = 'surat/'.
            $this->pengajuan->jenis_surat.
            '-'.
            $this->pengajuan->id.
            '-ttd-'.
            time().
            '.pdf';
        $pdf = Pdf::loadView($template, [
            'pengajuan' => $this->pengajuan,
            'sudahDitandatangani' => true,
            'dataSktm' => $dataSktm,
        ]);

        $pdf->setPaper('a4', 'portrait');

        // Simpan PDF baru
        Storage::disk('b2')->put(
            $filePath,
            $pdf->output()
        );

        $asal = $this->pengajuan->status;

        try {

            DB::transaction(function () use ($filePath) {

                $this->pengajuan->tanggal_selesai = now();
                $this->pengajuan->file_pdf = $filePath;

                app(PengajuanStatusService::class)->apply(
                    $this->pengajuan,
                    PengajuanSuratStatus::SELESAI,
                    Auth::user(),
                );
            });
        } catch (\Throwable $e) {

            // Kalau update database gagal,
            // hapus PDF baru supaya tidak meninggalkan file sampah
            Storage::disk('b2')->delete($filePath);

            throw $e;
        }

        app(PengajuanStatusService::class)->kirimNotifikasi(
            $this->pengajuan,
            $asal,
            PengajuanSuratStatus::SELESAI,
        );

        // Hapus PDF lama setelah PDF baru berhasil disimpan
        if (
            $oldFilePath &&
            $oldFilePath !== $filePath &&
            Storage::disk('b2')->exists($oldFilePath)
        ) {
            Storage::disk('b2')->delete($oldFilePath);
        }

        $this->pengajuan->refresh();

        $this->showModalTandaTangan = false;

        session()->flash(
            'success',
            'Surat berhasil ditandatangani dan diselesaikan.'
        );
    }

    public function bukaModalTandaTangan(): void
    {
        if (
            $this->pengajuan->status !==
            PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN
        ) {
            return;
        }
        $this->showModalTandaTangan = true;
    }

    public function render()
    {
        return view('livewire.kepala-desa.detail-persetujuan');
    }
}
