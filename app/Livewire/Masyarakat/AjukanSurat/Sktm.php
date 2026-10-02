<?php

namespace App\Livewire\Masyarakat\AjukanSurat;

use App\Enums\PengajuanSuratStatus;
use App\Models\PengajuanSurat;
use App\Models\PengajuanSuratData;
use App\Models\Penduduk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Sktm extends Component
{
    public Penduduk $penduduk;
    public string $keperluan = '';
    public ?PengajuanSurat $pengajuan = null;
    public bool $modeRevisi = false;
    public string $nik = '';
    public string $no_kk = '';
    public string $nama = '';
    public string $tempat_lahir = '';
    public string $tanggal_lahir = '';
    public string $jenis_kelamin = '';
    public string $alamat = '';
    public string $rt = '';
    public string $rw = '';
    public string $dusun = '';
    public string $pekerjaanAyah = '';
    public string $penghasilanAyah = '';
    public string $pekerjaanIbu = '';
    public string $penghasilanIbu = '';
    public function mount(?PengajuanSurat $pengajuan = null): void
    {
        $penduduk = Auth::user()?->penduduk;
        abort_unless($penduduk, 404);
        $this->penduduk = $penduduk;
        // ==========================================
        // DATA PEMOHON
        // ==========================================
        $this->nik = $penduduk->nik;
        $this->no_kk = $penduduk->no_kk;
        $this->nama = $penduduk->nama;
        $this->tempat_lahir = $penduduk->tempat_lahir;
        $this->tanggal_lahir = $penduduk->tanggal_lahir?->format('Y-m-d') ?? '';
        $this->jenis_kelamin = $penduduk->jenis_kelamin;
        $this->alamat = $penduduk->alamat;
        $this->rt = $penduduk->rt;
        $this->rw = $penduduk->rw;
        $this->dusun = $penduduk->dusun;
        // ==========================================
        // MODE REVISI
        // ==========================================
        if ($pengajuan) {
            abort_unless(
                $pengajuan->penduduk_id === $this->penduduk->id,
                403
            );
            abort_unless(
                $pengajuan->jenis_surat === 'sktm',
                404
            );
            abort_unless(
                $pengajuan->status === PengajuanSuratStatus::REVISI,
                404
            );
            $this->pengajuan = $pengajuan;
            $this->keperluan = $pengajuan->keperluan;
            $this->modeRevisi = true;
            $dataSktm = $pengajuan
                ->data()
                ->pluck('value', 'field');
            $this->pekerjaanAyah = $dataSktm['pekerjaan_ayah'] ?? '';
            $this->penghasilanAyah = $dataSktm['penghasilan_ayah'] ?? '';
            $this->pekerjaanIbu = $dataSktm['pekerjaan_ibu'] ?? '';
            $this->penghasilanIbu = $dataSktm['penghasilan_ibu'] ?? '';
        }
    }
    protected function rules(): array
    {
        return [
            'nik' => [
                'required',
                'digits:16',
            ],
            'no_kk' => [
                'required',
                'digits:16',
            ],
            'nama' => [
                'required',
                'string',
                'max:255',
            ],
            'tempat_lahir' => [
                'required',
                'string',
                'max:255',
            ],
            'tanggal_lahir' => [
                'required',
                'date',
            ],
            'jenis_kelamin' => [
                'required',
                'string',
            ],
            'alamat' => [
                'required',
                'string',
                'max:255',
            ],
            'rt' => [
                'required',
                'digits:3',
            ],
            'rw' => [
                'required',
                'digits:3',
            ],
            'dusun' => [
                'required',
                'string',
                'max:255',
            ],
            'pekerjaanAyah' => [
                'required',
                'string',
                'max:255',
            ],
            'penghasilanAyah' => [
                'required',
                'string',
                'max:255',
            ],
            'pekerjaanIbu' => [
                'required',
                'string',
                'max:255',
            ],
            'penghasilanIbu' => [
                'required',
                'string',
                'max:255',
            ],
            'keperluan' => [
                'required',
                'string',
                'max:1000',
            ],
        ];
    }
    public function ajukan(): void
    {
        $this->validate();
        // ==================================================
        // MODE REVISI
        // ==================================================
        if ($this->modeRevisi) {
            abort_unless($this->pengajuan, 404);
            DB::transaction(function () {
                $this->penduduk->update([
                    'nik' => $this->nik,
                    'no_kk' => $this->no_kk,
                    'nama' => $this->nama,
                    'tempat_lahir' => $this->tempat_lahir,
                    'tanggal_lahir' => $this->tanggal_lahir,
                    'jenis_kelamin' => $this->jenis_kelamin,
                    'alamat' => $this->alamat,
                    'rt' => $this->rt,
                    'rw' => $this->rw,
                    'dusun' => $this->dusun,
                ]);
                // ------------------------------------------
                // Update pengajuan surat
                // ------------------------------------------
                $this->pengajuan->update([
                    'keperluan' => $this->keperluan,
                    'status' => PengajuanSuratStatus::DIAJUKAN,
                    'catatan' => null,
                ]);
                // ------------------------------------------
                // Update data tambahan SKTM
                // ------------------------------------------
                PengajuanSuratData::updateOrCreate(
                    [
                        'pengajuan_surat_id' => $this->pengajuan->id,
                        'field' => 'pekerjaan_ayah',
                    ],
                    [
                        'value' => $this->pekerjaanAyah,
                    ]
                );
                PengajuanSuratData::updateOrCreate(
                    [
                        'pengajuan_surat_id' => $this->pengajuan->id,
                        'field' => 'penghasilan_ayah',
                    ],
                    [
                        'value' => $this->penghasilanAyah,
                    ]
                );
                PengajuanSuratData::updateOrCreate(
                    [
                        'pengajuan_surat_id' => $this->pengajuan->id,
                        'field' => 'pekerjaan_ibu',
                    ],
                    [
                        'value' => $this->pekerjaanIbu,
                    ]
                );
                PengajuanSuratData::updateOrCreate(
                    [
                        'pengajuan_surat_id' => $this->pengajuan->id,
                        'field' => 'penghasilan_ibu',
                    ],
                    [
                        'value' => $this->penghasilanIbu,
                    ]
                );
            });
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
        // ==================================================
        // PENGAJUAN BARU
        // ==================================================
        DB::transaction(function () {
            // ------------------------------------------
            // Simpan pengajuan utama
            // ------------------------------------------
            $pengajuan = PengajuanSurat::create([
                'penduduk_id' => $this->penduduk->id,
                'jenis_surat' => 'sktm',
                'keperluan' => $this->keperluan,
                'status' => PengajuanSuratStatus::DIAJUKAN->value,
            ]);
            // ------------------------------------------
            // Simpan data tambahan SKTM
            // ------------------------------------------
            PengajuanSuratData::create([
                'pengajuan_surat_id' => $pengajuan->id,
                'field' => 'pekerjaan_ayah',
                'value' => $this->pekerjaanAyah,
            ]);
            PengajuanSuratData::create([
                'pengajuan_surat_id' => $pengajuan->id,
                'field' => 'penghasilan_ayah',
                'value' => $this->penghasilanAyah,
            ]);
            PengajuanSuratData::create([
                'pengajuan_surat_id' => $pengajuan->id,
                'field' => 'pekerjaan_ibu',
                'value' => $this->pekerjaanIbu,
            ]);
            PengajuanSuratData::create([
                'pengajuan_surat_id' => $pengajuan->id,
                'field' => 'penghasilan_ibu',
                'value' => $this->penghasilanIbu,
            ]);
        });
        session()->flash(
            'success',
            'Pengajuan Surat Keterangan Tidak Mampu berhasil diajukan.'
        );
        $this->redirectRoute(
            'masyarakat.surat.create',
            navigate: true
        );
    }
    public function render()
    {
        return view('livewire.masyarakat.ajukan-surat.sktm');
    }
}
