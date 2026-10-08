<?php

namespace App\Livewire\PerangkatDesa;

use App\Enums\PengajuanSuratStatus;
use App\Models\Penduduk;
use App\Models\PengajuanSurat;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        $pendudukPerKelamin = Penduduk::query()
            ->selectRaw('jenis_kelamin, count(*) as jumlah')
            ->groupBy('jenis_kelamin')
            ->pluck('jumlah', 'jenis_kelamin');

        $pengajuanPerStatus = PengajuanSurat::query()
            ->selectRaw('status, count(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status');

        $pengajuanTerbaru = PengajuanSurat::with('penduduk')
            ->latest()
            ->limit(5)
            ->get();

        return view('livewire.perangkat-desa.dashboard', [
            'totalPenduduk' => (int) $pendudukPerKelamin->sum(),
            'pendudukLakiLaki' => (int) ($pendudukPerKelamin['L'] ?? 0),
            'pendudukPerempuan' => (int) ($pendudukPerKelamin['P'] ?? 0),
            'totalPengajuan' => (int) $pengajuanPerStatus->sum(),
            'pengajuanDiajukan' => (int) ($pengajuanPerStatus[PengajuanSuratStatus::DIAJUKAN->value] ?? 0),
            'pengajuanDiproses' => (int) ($pengajuanPerStatus[PengajuanSuratStatus::DIPROSES->value] ?? 0),
            'pengajuanMenungguTandaTangan' => (int) ($pengajuanPerStatus[PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN->value] ?? 0),
            'pengajuanSelesai' => (int) ($pengajuanPerStatus[PengajuanSuratStatus::SELESAI->value] ?? 0),
            'pengajuanTerbaru' => $pengajuanTerbaru,
        ]);
    }
}
