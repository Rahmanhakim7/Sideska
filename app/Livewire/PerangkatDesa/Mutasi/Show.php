<?php

namespace App\Livewire\PerangkatDesa\Mutasi;

use App\Models\MutasiPenduduk;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public MutasiPenduduk $mutasiPenduduk;

    public function mount(MutasiPenduduk $mutasiPenduduk): void
    {
        $this->mutasiPenduduk = $mutasiPenduduk->load(['penduduk', 'user']);
    }

    public function render()
    {
        return view('livewire.perangkat-desa.mutasi.show');
    }
}
