<?php

namespace App\Livewire\Admin\Pengguna;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Detail extends Component
{
    public User $user;

    public function mount(User $user): void
    {
        $this->user = $user->load('penduduk');
    }

    public function render()
    {
        return view('livewire.admin.pengguna.detail');
    }
}
