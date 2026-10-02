<?php

namespace App\Livewire\KepalaDesa;

use App\Enums\PengajuanSuratStatus;
use App\Models\PengajuanSurat;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Persetujuan extends Component
{
    use WithPagination;

    public function render()
    {
        $pengajuan = PengajuanSurat::with('penduduk')
            ->where(
                'status',
                PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN
            )
            ->latest()
            ->paginate(5);

        return view('livewire.kepala-desa.persetujuan', [
            'pengajuan' => $pengajuan,
        ]);
    }
}
