<?php

namespace App\Livewire\PerangkatDesa\Penduduk;

use App\Models\Penduduk;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public Penduduk $penduduk;

    public function mount(Penduduk $penduduk): void
    {
        $this->penduduk = $penduduk->load('user');
    }

    public function render()
    {
        return view(
            'livewire.perangkat-desa.penduduk.show'
        );
    }
}
