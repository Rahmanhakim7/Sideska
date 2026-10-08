<?php

namespace App\Livewire\KepalaDesa;

use App\Enums\PengajuanSuratStatus;
use App\Models\PengajuanSurat;
use Barryvdh\DomPDF\Facade\Pdf;
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

        $this->pengajuan = $pengajuan->load('penduduk');
    }

    public function tandaTangani(): void
    {
        if (
            $this->pengajuan->status !==
            PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN
        ) {
            return;
        }
        $this->pengajuan->load('penduduk');
        $oldFilePath = $this->pengajuan->file_pdf;
        $filePath = 'surat/domisili-'.
            $this->pengajuan->id.
            '-ttd-'.
            time().
            '.pdf';
        $pdf = Pdf::loadView('surat.domisili', [
            'pengajuan' => $this->pengajuan,
            'sudahDitandatangani' => true,
        ]);

        $pdf->setPaper('a4', 'portrait');

        // Simpan PDF baru
        Storage::disk('b2')->put(
            $filePath,
            $pdf->output()
        );

        try {

            DB::transaction(function () use ($filePath) {

                $this->pengajuan->update([
                    'status' => PengajuanSuratStatus::SELESAI,
                    'tanggal_selesai' => now(),
                    'file_pdf' => $filePath,
                ]);
            });
        } catch (\Throwable $e) {

            // Kalau update database gagal,
            // hapus PDF baru supaya tidak meninggalkan file sampah
            Storage::disk('b2')->delete($filePath);

            throw $e;
        }

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
