<?php

namespace App\Livewire\PerangkatDesa\MutasiKeluar;

use App\Models\Penduduk;
use App\Services\MutasiPendudukService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Create extends Component
{
    public Penduduk $penduduk;

    public string $tanggal_mutasi = '';

    public string $alamat_tujuan = '';

    public ?string $alasan = null;

    public ?string $catatan = null;

    public function mount(Penduduk $penduduk): void
    {
        $this->penduduk = $penduduk;
    }

    protected function rules(): array
    {
        return [
            'tanggal_mutasi' => [
                'required',
                'date',
            ],
            'alamat_tujuan' => [
                'required',
                'string',
                'max:1000',
            ],
            'alasan' => [
                'nullable',
                'string',
                'max:255',
            ],
            'catatan' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'tanggal_mutasi' => 'tanggal mutasi',
            'alamat_tujuan' => 'alamat tujuan',
            'alasan' => 'alasan',
            'catatan' => 'catatan',
        ];
    }

    public function simpan()
    {
        abort_unless(
            auth()->user()?->role === 'perangkat_desa',
            403
        );

        $this->validate();

        app(MutasiPendudukService::class)->mutasiKeluar($this->penduduk->id, [
            'tanggal_mutasi' => $this->tanggal_mutasi,
            'alamat_tujuan' => $this->alamat_tujuan,
            'alasan' => $this->alasan,
            'catatan' => $this->catatan,
        ]);

        session()->flash('success', 'Mutasi keluar berhasil dicatat.');

        return $this->redirectRoute('perangkat-desa.penduduk.index');
    }

    public function render()
    {
        return view('livewire.perangkat-desa.mutasi-keluar.create', [
            'alamatAsal' => app(MutasiPendudukService::class)
                ->alamatSnapshot($this->penduduk),
        ]);
    }
}
