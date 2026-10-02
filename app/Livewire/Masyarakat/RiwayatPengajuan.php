<?php

namespace App\Livewire\Masyarakat;

use App\Models\PengajuanSurat;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class RiwayatPengajuan extends Component
{
    use WithPagination;

    public function routePerbaikan(PengajuanSurat $pengajuan): string
    {
        return match ($pengajuan->jenis_surat) {
            'domisili' => route('masyarakat.ajukan-surat.domisili', [
                'pengajuan' => $pengajuan->id,
            ]),

            default => '#',
        };
    }

    public function render()
    {
        $pengajuan = PengajuanSurat::where(
            'penduduk_id',
            Auth::user()->penduduk->id
        )
            ->latest()
            ->paginate(5);

        return view('livewire.masyarakat.riwayat-pengajuan', [
            'pengajuan' => $pengajuan,
        ]);
    }
}
