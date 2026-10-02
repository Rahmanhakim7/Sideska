<?php

namespace App\Livewire\Masyarakat;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AjukanSurat extends Component
{
    public function render()
    {
        return view('livewire.masyarakat.ajukan-surat');
    }
}
