<?php

namespace App\Livewire\PerangkatDesa\Penduduk;

use App\Models\Penduduk as PendudukModel;
use App\Services\PendudukFotoService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $loaded = false;

    public bool $showModalHapus = false;

    public ?PendudukModel $pendudukAkanDihapus = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function loadData(): void
    {
        $this->loaded = true;
    }

    public function hapus(int $id): void
    {
        abort_unless(
            auth()->user()?->role === 'perangkat_desa',
            403
        );

        $penduduk = PendudukModel::with('user')->findOrFail($id);

        $user = $penduduk->user;
        $foto = $penduduk->foto;

        DB::transaction(function () use ($penduduk, $user): void {
            $penduduk->delete();

            $user?->delete();
        });

        app(PendudukFotoService::class)->hapus($foto);
    }

    public function konfirmasiHapus(int $id): void
    {
        $this->pendudukAkanDihapus = PendudukModel::findOrFail($id);

        $this->showModalHapus = true;
    }

    public function hapusPenduduk(): void
    {
        if ($this->pendudukAkanDihapus === null) {
            return;
        }

        $this->hapus($this->pendudukAkanDihapus->id);

        $this->pendudukAkanDihapus = null;

        $this->showModalHapus = false;
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
                            '%'.$this->search.'%'
                        )->orWhere(
                            'nama',
                            'ilike',
                            '%'.$this->search.'%'
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
