<?php

namespace App\Livewire\Masyarakat\AjukanSurat;

use App\Enums\PengajuanSuratStatus;
use App\Models\Penduduk;
use App\Models\PengajuanSurat;
use App\Models\PengajuanSuratData;
use App\Services\PengajuanStatusService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class KeteranganPindah extends Component
{
    public Penduduk $penduduk;

    public string $keperluan = '';

    public ?PengajuanSurat $pengajuan = null;

    public bool $modeRevisi = false;

    public string $alasanPindah = '';

    public string $alamatTujuan = '';

    public $rtTujuan = '';

    public $rwTujuan = '';

    public string $desaKelurahanTujuan = '';

    public string $kecamatanTujuan = '';

    public string $kabupatenTujuan = '';

    public string $provinsiTujuan = '';

    public $klasifikasiPindah = '';

    public $jumlahAnggotaPindah = '';

    public $tanggalPindah = '';

    public function mount(?PengajuanSurat $pengajuan = null): void
    {
        $penduduk = Auth::user()?->penduduk;
        abort_unless($penduduk, 404);
        $this->penduduk = $penduduk;

        if ($pengajuan) {
            abort_unless(
                $pengajuan->penduduk_id === $this->penduduk->id,
                403
            );
            abort_unless(
                $pengajuan->jenis_surat === 'keterangan_pindah',
                404
            );
            abort_unless(
                $pengajuan->status === PengajuanSuratStatus::REVISI,
                404
            );

            $this->pengajuan = $pengajuan;
            $this->keperluan = $pengajuan->keperluan;
            $this->modeRevisi = true;

            $dataPindah = $pengajuan
                ->data()
                ->pluck('value', 'field');

            $this->alasanPindah = $dataPindah['alasan_pindah'] ?? '';
            $this->alamatTujuan = $dataPindah['alamat_tujuan'] ?? '';
            $this->rtTujuan = $dataPindah['rt_tujuan'] ?? '';
            $this->rwTujuan = $dataPindah['rw_tujuan'] ?? '';
            $this->desaKelurahanTujuan = $dataPindah['desa_kelurahan_tujuan'] ?? '';
            $this->kecamatanTujuan = $dataPindah['kecamatan_tujuan'] ?? '';
            $this->kabupatenTujuan = $dataPindah['kabupaten_tujuan'] ?? '';
            $this->provinsiTujuan = $dataPindah['provinsi_tujuan'] ?? '';
            $this->klasifikasiPindah = $dataPindah['klasifikasi_pindah'] ?? '';
            $this->jumlahAnggotaPindah = $dataPindah['jumlah_anggota_pindah'] ?? '';
            $this->tanggalPindah = $dataPindah['tanggal_pindah'] ?? '';
        }
    }

    protected function rules(): array
    {
        return [
            'alasanPindah' => [
                'required',
                'string',
                'max:255',
            ],

            'alamatTujuan' => [
                'required',
                'string',
                'max:500',
            ],

            'rtTujuan' => [
                'nullable',
                'string',
                'max:10',
            ],

            'rwTujuan' => [
                'nullable',
                'string',
                'max:10',
            ],

            'desaKelurahanTujuan' => [
                'required',
                'string',
                'max:100',
            ],

            'kecamatanTujuan' => [
                'required',
                'string',
                'max:100',
            ],

            'kabupatenTujuan' => [
                'required',
                'string',
                'max:100',
            ],

            'provinsiTujuan' => [
                'required',
                'string',
                'max:100',
            ],

            'klasifikasiPindah' => [
                'nullable',
                'string',
                'max:100',
            ],

            'jumlahAnggotaPindah' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'tanggalPindah' => [
                'nullable',
                'date',
            ],

            'keperluan' => [
                'required',
                'string',
                'max:500',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function dataPindah(): array
    {
        return [
            'alasan_pindah' => $this->alasanPindah,
            'alamat_tujuan' => $this->alamatTujuan,
            'rt_tujuan' => $this->rtTujuan,
            'rw_tujuan' => $this->rwTujuan,
            'desa_kelurahan_tujuan' => $this->desaKelurahanTujuan,
            'kecamatan_tujuan' => $this->kecamatanTujuan,
            'kabupaten_tujuan' => $this->kabupatenTujuan,
            'provinsi_tujuan' => $this->provinsiTujuan,
            'klasifikasi_pindah' => $this->klasifikasiPindah,
            'jumlah_anggota_pindah' => $this->jumlahAnggotaPindah,
            'tanggal_pindah' => $this->tanggalPindah,
        ];
    }

    private function simpanDataPindah(PengajuanSurat $pengajuan): void
    {
        foreach ($this->dataPindah() as $field => $value) {
            PengajuanSuratData::updateOrCreate(
                [
                    'pengajuan_surat_id' => $pengajuan->id,
                    'field' => $field,
                ],
                [
                    'value' => $value,
                ]
            );
        }
    }

    public function ajukan(): void
    {
        $this->jumlahAnggotaPindah = $this->jumlahAnggotaPindah === ''
            ? null
            : $this->jumlahAnggotaPindah;
        $this->tanggalPindah = $this->tanggalPindah === ''
            ? null
            : $this->tanggalPindah;

        $this->validate();

        if ($this->modeRevisi) {
            abort_unless($this->pengajuan, 404);
            $asal = $this->pengajuan->status;

            DB::transaction(function () {
                $this->pengajuan->keperluan = $this->keperluan;
                $this->pengajuan->catatan = null;

                app(PengajuanStatusService::class)->apply(
                    $this->pengajuan,
                    PengajuanSuratStatus::DIAJUKAN,
                    Auth::user(),
                );

                $this->simpanDataPindah($this->pengajuan);
            });

            app(PengajuanStatusService::class)->kirimNotifikasi(
                $this->pengajuan,
                $asal,
                PengajuanSuratStatus::DIAJUKAN,
            );

            session()->flash(
                'success',
                'Perbaikan pengajuan berhasil dikirim kembali.'
            );
            $this->redirectRoute(
                'masyarakat.surat.riwayat',
                navigate: true
            );

            return;
        }

        $service = app(PengajuanStatusService::class);
        $pengajuan = null;

        DB::transaction(function () use ($service, &$pengajuan) {
            $pengajuan = PengajuanSurat::create([
                'penduduk_id' => $this->penduduk->id,
                'jenis_surat' => 'keterangan_pindah',
                'keperluan' => $this->keperluan,
                'status' => PengajuanSuratStatus::DIAJUKAN->value,
            ]);

            $service->awali($pengajuan, Auth::user());

            $this->simpanDataPindah($pengajuan);
        });

        $service->kirimNotifikasi(
            $pengajuan,
            null,
            PengajuanSuratStatus::DIAJUKAN,
        );

        session()->flash(
            'success',
            'Pengajuan Surat Keterangan Pindah berhasil diajukan.'
        );
        $this->redirectRoute(
            'masyarakat.surat.create',
            navigate: true
        );
    }

    public function render()
    {
        return view('livewire.masyarakat.ajukan-surat.keterangan-pindah');
    }
}
