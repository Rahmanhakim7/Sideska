<?php

namespace App\Livewire\PerangkatDesa;

use App\Enums\PengajuanSuratStatus;
use App\Models\PengajuanSurat;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class DetailPelayananSurat extends Component
{
    public PengajuanSurat $pengajuan;
    public bool $showModalTolak = false;
    public string $alasanPenolakan = '';
    public bool $showModalRevisi = false;
    public string $alasanRevisi = '';
    public function mount(PengajuanSurat $pengajuan): void
    {
        $this->pengajuan = $pengajuan->load([
            'penduduk',
            'data',
        ]);
    }
    public function bukaModalTolak(): void
    {
        $this->showModalTolak = true;
    }
    public function bukaModalRevisi(): void
    {
        $this->showModalRevisi = true;
    }
    public function mintaRevisi(): void
    {
        if ($this->pengajuan->status !== PengajuanSuratStatus::DIAJUKAN) {
            return;
        }

        $this->validate([
            'alasanRevisi' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'alasanRevisi.required' => 'Alasan revisi wajib diisi.',
            'alasanRevisi.min' => 'Alasan revisi minimal 5 karakter.',
            'alasanRevisi.max' => 'Alasan revisi maksimal 1000 karakter.',
        ]);

        DB::transaction(function () {
            $this->pengajuan->update([
                'status' => PengajuanSuratStatus::REVISI,
                'catatan' => $this->alasanRevisi,
            ]);
        });

        $this->pengajuan->refresh();
        $this->pengajuan->load('penduduk');
        $this->showModalRevisi = false;
        $this->alasanRevisi = '';
    }

    public function terima(): void
    {
        if ($this->pengajuan->status !== PengajuanSuratStatus::DIAJUKAN) {
            return;
        }
        $this->ubahStatus(PengajuanSuratStatus::DIPROSES);
    }
    public function tolak(): void
    {
        if ($this->pengajuan->status !== PengajuanSuratStatus::DIAJUKAN) {
            return;
        }
        $this->validate([
            'alasanPenolakan' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'alasanPenolakan.required' => 'Alasan penolakan wajib diisi.',
            'alasanPenolakan.min' => 'Alasan penolakan minimal 5 karakter.',
            'alasanPenolakan.max' => 'Alasan penolakan maksimal 1000 karakter.',
        ]);
        DB::transaction(function () {
            $this->pengajuan->update([
                'status' => PengajuanSuratStatus::DITOLAK,
                'catatan' => $this->alasanPenolakan,
            ]);
        });
        $this->pengajuan->refresh();
        $this->pengajuan->load('penduduk');
        $this->showModalTolak = false;
        $this->alasanPenolakan = '';
    }
    public function prosesSurat(): void
    {
        if ($this->pengajuan->status !== PengajuanSuratStatus::DIPROSES) {
            return;
        }
        $this->ubahStatus(
            PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN
        );
    }
    protected function ubahStatus(PengajuanSuratStatus $status): void
    {
        DB::transaction(function () use ($status) {
            $this->pengajuan->update([
                'status' => $status,
            ]);
        });
        $this->pengajuan->refresh();
        $this->pengajuan->load('penduduk');
    }

    public function render()
    {
        $statusLabel = match ($this->pengajuan->status) {
            PengajuanSuratStatus::DIAJUKAN => 'Diajukan',
            PengajuanSuratStatus::REVISI => 'Revisi',
            PengajuanSuratStatus::DIPROSES => 'Diproses',
            PengajuanSuratStatus::DITOLAK => 'Ditolak',
            PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN => 'Menunggu Tanda Tangan',
            PengajuanSuratStatus::SELESAI => 'Selesai',
        };
        $dataSktm = $this->pengajuan->data
            ->pluck('value', 'field');
        return view(
            'livewire.perangkat-desa.detail-pelayanan-surat',
            [
                'statusLabel' => $statusLabel,
                'dataSktm' => $dataSktm,
            ]
        );
    }
}
