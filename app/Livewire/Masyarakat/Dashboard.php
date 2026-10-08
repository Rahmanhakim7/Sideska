<?php

namespace App\Livewire\Masyarakat;

use App\Enums\PengajuanSuratStatus;
use App\Models\PengajuanSurat;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        $penduduk = Auth::user()?->penduduk;
        $pengajuanPerStatus = collect();
        $pengajuanTerbaru = collect();

        if ($penduduk !== null) {
            $pengajuanPerStatus = PengajuanSurat::where('penduduk_id', $penduduk->id)
                ->selectRaw('status, count(*) as jumlah')
                ->groupBy('status')
                ->pluck('jumlah', 'status');

            $pengajuanTerbaru = PengajuanSurat::where('penduduk_id', $penduduk->id)
                ->latest()
                ->limit(5)
                ->get();
        }

        return view('livewire.masyarakat.dashboard', [
            'totalPengajuan' => (int) $pengajuanPerStatus->sum(),
            'pengajuanDiajukan' => (int) ($pengajuanPerStatus[PengajuanSuratStatus::DIAJUKAN->value] ?? 0),
            'pengajuanDiproses' => (int) ($pengajuanPerStatus[PengajuanSuratStatus::DIPROSES->value] ?? 0),
            'pengajuanRevisi' => (int) ($pengajuanPerStatus[PengajuanSuratStatus::REVISI->value] ?? 0),
            'pengajuanDitolak' => (int) ($pengajuanPerStatus[PengajuanSuratStatus::DITOLAK->value] ?? 0),
            'pengajuanSelesai' => (int) ($pengajuanPerStatus[PengajuanSuratStatus::SELESAI->value] ?? 0),
            'pengajuanTerbaru' => $pengajuanTerbaru,
        ]);
    }
}
