<?php

namespace App\Livewire\KepalaDesa;

use App\Enums\PengajuanSuratStatus;
use App\Models\PengajuanSurat;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        $pengajuanPerStatus = PengajuanSurat::query()
            ->selectRaw('status, count(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status');

        $menungguTandaTangan = PengajuanSurat::with('penduduk')
            ->where('status', PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN)
            ->latest()
            ->limit(5)
            ->get();

        return view('livewire.kepala-desa.dashboard', [
            'totalPengajuan' => (int) $pengajuanPerStatus->sum(),
            'pengajuanMenungguTandaTangan' => (int) ($pengajuanPerStatus[PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN->value] ?? 0),
            'pengajuanSelesai' => (int) ($pengajuanPerStatus[PengajuanSuratStatus::SELESAI->value] ?? 0),
            'pengajuanDitolak' => (int) ($pengajuanPerStatus[PengajuanSuratStatus::DITOLAK->value] ?? 0),
            'daftarMenungguTandaTangan' => $menungguTandaTangan,
        ]);
    }
}
