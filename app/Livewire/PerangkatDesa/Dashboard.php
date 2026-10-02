<?php

namespace App\Livewire\PerangkatDesa;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.perangkat-desa.dashboard');
    }
}