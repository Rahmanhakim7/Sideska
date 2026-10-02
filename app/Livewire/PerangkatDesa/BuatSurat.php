<?php

namespace App\Livewire\PerangkatDesa;

use App\Enums\PengajuanSuratStatus;
use App\Models\PengajuanSurat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class BuatSurat extends Component
{
    public PengajuanSurat $pengajuan;
    public array $fields = [];
    public array $data = [];

    public function mount(PengajuanSurat $pengajuan): void
    {
        $this->pengajuan = $pengajuan->load([
            'penduduk',
            'data',
        ]);
        $this->fields = $this->getFields();
        foreach ($this->fields as $field) {
            $existingData = $this->pengajuan->data
                ->firstWhere('field', $field['name']);

            $this->data[$field['name']] = $existingData?->value;
        }
        $this->data['nomor_surat'] = $this->pengajuan->nomor_surat;
        $this->data['tanggal_surat'] = $this->pengajuan->tanggal_surat;
    }

    protected function getFields(): array
    {
        return match ($this->pengajuan->jenis_surat) {
            'domisili' => [
                [
                    'name' => 'nomor_surat',
                    'label' => 'Nomor Surat',
                    'type' => 'text',
                    'required' => true,
                ],
                [
                    'name' => 'tanggal_surat',
                    'label' => 'Tanggal Surat',
                    'type' => 'date',
                    'required' => true,
                ],
            ],
            'sktm' => [
                [
                    'name' => 'nomor_surat',
                    'label' => 'Nomor Surat',
                    'type' => 'text',
                    'required' => true,
                ],
                [
                    'name' => 'tanggal_surat',
                    'label' => 'Tanggal Surat',
                    'type' => 'date',
                    'required' => true,
                ],
            ],
            default => [],
        };
    }

    public function simpan(): void
    {
        $this->validate([
            'data.nomor_surat' => [
                'required',
                'string',
                'max:255',
            ],

            'data.tanggal_surat' => [
                'required',
                'date',
            ],
        ], [
            'data.nomor_surat.required' => 'Nomor surat wajib diisi.',
            'data.nomor_surat.max' => 'Nomor surat maksimal 255 karakter.',
            'data.tanggal_surat.required' => 'Tanggal surat wajib diisi.',
            'data.tanggal_surat.date' => 'Tanggal surat tidak valid.',
        ]);
        $this->pengajuan->nomor_surat = $this->data['nomor_surat'];
        $this->pengajuan->tanggal_surat = $this->data['tanggal_surat'];
        $template = match ($this->pengajuan->jenis_surat) {
            'domisili' => 'surat.domisili',
            'sktm' => 'surat.sktm',
            default => null,
        };
        abort_unless($template, 404);
        $dataSktm = $this->pengajuan
            ->data
            ->pluck('value', 'field');
        $pdf = Pdf::loadView(
            $template,
            [
                'pengajuan' => $this->pengajuan,
                'dataSktm' => $dataSktm,
            ]
        );
        $pdf->setPaper('a4', 'portrait');
        $filePath = 'surat/'
            . $this->pengajuan->jenis_surat
            . '-'
            . $this->pengajuan->id
            . '-'
            . time()
            . '.pdf';
        Storage::disk('public')->put(
            $filePath,
            $pdf->output()
        );
        DB::transaction(function () use ($filePath) {
            $this->pengajuan->update([
                'nomor_surat' => $this->data['nomor_surat'],
                'tanggal_surat' => $this->data['tanggal_surat'],
                'file_pdf' => $filePath,
                'status' => PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN->value,
            ]);
        });
        session()->flash(
            'success',
            'Surat berhasil dibuat dan PDF berhasil disimpan.'
        );
        $this->redirect(
            route(
                'perangkat-desa.pelayanan-surat.detail',
                $this->pengajuan->id
            ),
            navigate: true
        );
    }

    public function render()
    {
        return view(
            'livewire.perangkat-desa.buat-surat'
        );
    }
}
