<?php

namespace App\Livewire\Admin\Pengguna;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $pengguna = User::query()
            ->with('penduduk')
            ->when($this->search !== '', function ($query) {
                $query->where(function ($query) {
                    $query->where(
                        'name',
                        'ilike',
                        '%'.$this->search.'%'
                    )->orWhere(
                        'email',
                        'ilike',
                        '%'.$this->search.'%'
                    );
                });
            })
            ->latest()
            ->paginate(15);

        return view('livewire.admin.pengguna.index', [
            'pengguna' => $pengguna,
        ]);
    }
}
