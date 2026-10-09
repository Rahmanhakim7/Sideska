<?php

namespace App\Livewire\Masyarakat\AjukanSurat;

use App\Enums\PengajuanSuratStatus;
use App\Models\Penduduk;
use App\Models\PengajuanSurat;
use App\Models\PengajuanSuratData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Usaha extends Component
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

    public string $namaUsaha = '';

    public string $jenisUsaha = '';

    public string $alamatUsaha = '';

    public string $lamaUsaha = '';

    public string $modalUsaha = '';

    public function mount(?PengajuanSurat $pengajuan = null): void
    {
        $penduduk = Auth::user()?->penduduk;
        abort_unless($penduduk, 404);
        $this->penduduk = $penduduk;
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
        if ($pengajuan) {
            abort_unless(
                $pengajuan->penduduk_id === $this->penduduk->id,
                403
            );
            abort_unless(
                $pengajuan->jenis_surat === 'usaha',
                404
            );
            abort_unless(
                $pengajuan->status === PengajuanSuratStatus::REVISI,
                404
            );
            $this->pengajuan = $pengajuan;
            $this->keperluan = $pengajuan->keperluan;
            $this->modeRevisi = true;
            $dataUsaha = $pengajuan
                ->data()
                ->pluck('value', 'field');
            $this->namaUsaha = $dataUsaha['nama_usaha'] ?? '';
            $this->jenisUsaha = $dataUsaha['jenis_usaha'] ?? '';
            $this->alamatUsaha = $dataUsaha['alamat_usaha'] ?? '';
            $this->lamaUsaha = $dataUsaha['lama_usaha'] ?? '';
            $this->modalUsaha = $dataUsaha['modal_usaha'] ?? '';
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

            'namaUsaha' => [
                'required',
                'string',
                'max:255',
            ],

            'jenisUsaha' => [
                'required',
                'string',
                'max:255',
            ],

            'alamatUsaha' => [
                'required',
                'string',
                'max:1000',
            ],

            'lamaUsaha' => [
                'required',
                'string',
                'max:255',
            ],

            'modalUsaha' => [
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
                $this->pengajuan->update([
                    'keperluan' => $this->keperluan,
                    'status' => PengajuanSuratStatus::DIAJUKAN,
                    'catatan' => null,
                ]);
                PengajuanSuratData::updateOrCreate(
                    [
                        'pengajuan_surat_id' => $this->pengajuan->id,
                        'field' => 'nama_usaha',
                    ],
                    [
                        'value' => $this->namaUsaha,
                    ]
                );
                PengajuanSuratData::updateOrCreate(
                    [
                        'pengajuan_surat_id' => $this->pengajuan->id,
                        'field' => 'jenis_usaha',
                    ],
                    [
                        'value' => $this->jenisUsaha,
                    ]
                );
                PengajuanSuratData::updateOrCreate(
                    [
                        'pengajuan_surat_id' => $this->pengajuan->id,
                        'field' => 'alamat_usaha',
                    ],
                    [
                        'value' => $this->alamatUsaha,
                    ]
                );
                PengajuanSuratData::updateOrCreate(
                    [
                        'pengajuan_surat_id' => $this->pengajuan->id,
                        'field' => 'lama_usaha',
                    ],
                    [
                        'value' => $this->lamaUsaha,
                    ]
                );
                PengajuanSuratData::updateOrCreate(
                    [
                        'pengajuan_surat_id' => $this->pengajuan->id,
                        'field' => 'modal_usaha',
                    ],
                    [
                        'value' => $this->modalUsaha,
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
        DB::transaction(function () {
            $pengajuan = PengajuanSurat::create([
                'penduduk_id' => $this->penduduk->id,
                'jenis_surat' => 'usaha',
                'keperluan' => $this->keperluan,
                'status' => PengajuanSuratStatus::DIAJUKAN->value,
            ]);
            PengajuanSuratData::create([
                'pengajuan_surat_id' => $pengajuan->id,
                'field' => 'nama_usaha',
                'value' => $this->namaUsaha,
            ]);
            PengajuanSuratData::create([
                'pengajuan_surat_id' => $pengajuan->id,
                'field' => 'jenis_usaha',
                'value' => $this->jenisUsaha,
            ]);
            PengajuanSuratData::create([
                'pengajuan_surat_id' => $pengajuan->id,
                'field' => 'alamat_usaha',
                'value' => $this->alamatUsaha,
            ]);
            PengajuanSuratData::create([
                'pengajuan_surat_id' => $pengajuan->id,
                'field' => 'lama_usaha',
                'value' => $this->lamaUsaha,
            ]);
            PengajuanSuratData::create([
                'pengajuan_surat_id' => $pengajuan->id,
                'field' => 'modal_usaha',
                'value' => $this->modalUsaha,
            ]);
        });
        session()->flash(
            'success',
            'Pengajuan Surat Keterangan Usaha berhasil diajukan.'
        );
        $this->redirectRoute(
            'masyarakat.surat.create',
            navigate: true
        );
    }

    public function render()
    {
        return view('livewire.masyarakat.ajukan-surat.usaha');
    }
}
