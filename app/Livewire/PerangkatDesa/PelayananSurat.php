<?php

namespace App\Livewire\PerangkatDesa;

use App\Models\PengajuanSurat;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class PelayananSurat extends Component
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
            $pengajuan = new LengthAwarePaginator(
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
            $pengajuan = PengajuanSurat::with('penduduk')
                ->when($this->search !== '', function ($query) {
                    $query->whereHas('penduduk', function ($query) {
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
            'livewire.perangkat-desa.pelayanan-surat',
            [
                'pengajuan' => $pengajuan,
            ]
        );
    }
}
