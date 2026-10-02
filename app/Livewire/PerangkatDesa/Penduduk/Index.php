<?php

namespace App\Livewire\PerangkatDesa\Penduduk;

use App\Models\Penduduk as PendudukModel;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;
    public string $search = '';
    public bool $loaded = false;
    public function updatedSearch(): void
    {
        $this->resetPage();
    }
    public function loadData(): void
    {
        $this->loaded = true;
    }
    public function render()
    {
        if (! $this->loaded) {
            $penduduk = new LengthAwarePaginator(
                collect(),
                0,
                10,
                1,
                [
                    'path' => request()->url(),
                    'pageName' => 'page',
                ]
            );
        } else {
            $penduduk = PendudukModel::with('user')
                ->when($this->search !== '', function ($query) {
                    $query->where(function ($query) {
                        $query->where(
                            'nik',
                            'ilike',
                            '%' . $this->search . '%'
                        )->orWhere(
                            'nama',
                            'ilike',
                            '%' . $this->search . '%'
                        );
                    });
                })
                ->latest()
                ->paginate(
                    10,
                    ['*'],
                    'page'
                );
        }

        return view(
            'livewire.perangkat-desa.penduduk.index',
            [
                'penduduk' => $penduduk,
            ]
        );
    }
}
