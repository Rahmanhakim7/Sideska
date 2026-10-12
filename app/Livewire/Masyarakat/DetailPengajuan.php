<?php

namespace App\Livewire\Masyarakat;

use App\Enums\PengajuanSuratStatus;
use App\Models\PengajuanSurat;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class DetailPengajuan extends Component
{
    public PengajuanSurat $pengajuan;

    public function mount(PengajuanSurat $pengajuan): void
    {
        $penduduk = Auth::user()?->penduduk;

        abort_unless($penduduk, 404);
        abort_unless(
            $pengajuan->penduduk_id === $penduduk->id,
            403
        );

        $this->pengajuan = $pengajuan->load([
            'penduduk',
            'data',
            'statusHistories.user',
        ]);
    }

    public function routePerbaikan(): string
    {
        return match ($this->pengajuan->jenis_surat) {
            'domisili' => route('masyarakat.ajukan-surat.domisili', [
                'pengajuan' => $this->pengajuan->id,
            ]),

            'sktm' => route('masyarakat.ajukan-surat.sktm', [
                'pengajuan' => $this->pengajuan->id,
            ]),

            'usaha' => route('masyarakat.ajukan-surat.usaha', [
                'pengajuan' => $this->pengajuan->id,
            ]),

            'skck' => route('masyarakat.ajukan-surat.skck', [
                'pengajuan' => $this->pengajuan->id,
            ]),

            'keterangan_pindah' => route('masyarakat.ajukan-surat.keterangan-pindah', [
                'pengajuan' => $this->pengajuan->id,
            ]),

            default => '#',
        };
    }

    public function render()
    {
        $statusLabel = match ($this->pengajuan->status) {
            PengajuanSuratStatus::DIAJUKAN => 'Diajukan',
            PengajuanSuratStatus::REVISI => 'Revisi',
            PengajuanSuratStatus::DIPROSES => 'Diproses',
            PengajuanSuratStatus::DITOLAK => 'Ditolak',
            PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN => 'Menunggu Tanda Tangan',
            PengajuanSuratStatus::SELESAI => 'Selesai',
        };

        return view('livewire.masyarakat.detail-pengajuan', [
            'statusLabel' => $statusLabel,
        ]);
    }
}
