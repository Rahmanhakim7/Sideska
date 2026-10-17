<?php

namespace App\Livewire\Admin;

use App\Models\Penduduk;
use App\Models\PengajuanSurat;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        $userPerRole = User::query()
            ->selectRaw('role, count(*) as jumlah')
            ->groupBy('role')
            ->pluck('jumlah', 'role');

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

        return view('livewire.admin.dashboard', [
            'totalUser' => (int) $userPerRole->sum(),
            'userAktif' => User::query()->where('is_active', true)->count(),
            'userNonaktif' => User::query()->where('is_active', false)->count(),
            'userAdmin' => (int) ($userPerRole['admin'] ?? 0),
            'userPerangkatDesa' => (int) ($userPerRole['perangkat_desa'] ?? 0),
            'userKepalaDesa' => (int) ($userPerRole['kepala_desa'] ?? 0),
            'userMasyarakat' => (int) ($userPerRole['masyarakat'] ?? 0),
            'totalPenduduk' => (int) $pendudukPerKelamin->sum(),
            'pendudukLakiLaki' => (int) ($pendudukPerKelamin['L'] ?? 0),
            'pendudukPerempuan' => (int) ($pendudukPerKelamin['P'] ?? 0),
            'totalPengajuan' => (int) $pengajuanPerStatus->sum(),
            'pengajuanPerStatus' => $pengajuanPerStatus,
            'pengajuanTerbaru' => $pengajuanTerbaru,
        ]);
    }
}
