<?php

namespace App\Livewire\PerangkatDesa\Mutasi;

use App\Enums\JenisMutasi;
use App\Models\MutasiPenduduk;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $jenis = 'semua';

    public function updatedJenis(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = MutasiPenduduk::query()
            ->with(['penduduk', 'user'])
            ->orderByDesc('tanggal_mutasi')
            ->orderByDesc('created_at');

        if ($this->jenis === 'masuk') {
            $query->where('jenis', JenisMutasi::MASUK->value);
        }

        if ($this->jenis === 'keluar') {
            $query->where('jenis', JenisMutasi::KELUAR->value);
        }

        $mutasi = $query->paginate(15);

        return view('livewire.perangkat-desa.mutasi.index', [
            'mutasi' => $mutasi,
        ]);
    }
}
