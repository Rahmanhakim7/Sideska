<?php

namespace App\Livewire\PerangkatDesa\MutasiMasuk;

use App\Services\MutasiPendudukService;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Create extends Component
{
    public $email;

    public $password;

    public $password_confirmation;

    public $nik;

    public $no_kk;

    public $nama;

    public $tempat_lahir;

    public $tanggal_lahir;

    public $jenis_kelamin;

    public $agama;

    public $status_perkawinan;

    public $pendidikan;

    public $pekerjaan;

    public $status_hubungan_keluarga;

    public $golongan_darah;

    public $alamat;

    public $rt;

    public $rw;

    public $dusun;

    public $kewarganegaraan;

    public $no_hp;

    public $disabilitas;

    public $nama_ayah;

    public $nama_ibu;

    public $tanggal_mutasi;

    public $alamat_asal;

    public $alasan;

    public $catatan;

    protected function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'same:password_confirmation',
            ],
            'password_confirmation' => [
                'required',
                'string',
                'min:8',
            ],
            'nik' => [
                'required',
                'digits:16',
                'unique:penduduk,nik',
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
                Rule::in(['L', 'P']),
            ],
            'agama' => [
                'required',
                Rule::in([
                    'Islam',
                    'Kristen',
                    'Katolik',
                    'Hindu',
                    'Buddha',
                    'Konghucu',
                ]),
            ],
            'status_perkawinan' => [
                'required',
                Rule::in([
                    'Belum Kawin',
                    'Kawin',
                    'Cerai Hidup',
                    'Cerai Mati',
                ]),
            ],
            'pendidikan' => [
                'required',
                Rule::in([
                    'Tidak/Belum Sekolah',
                    'SD/Sederajat',
                    'SMP/Sederajat',
                    'SMA/Sederajat',
                    'Diploma',
                    'S1',
                    'S2',
                    'S3',
                ]),
            ],
            'pekerjaan' => [
                'required',
                'string',
                'max:255',
            ],
            'status_hubungan_keluarga' => [
                'required',
                Rule::in([
                    'Kepala Keluarga',
                    'Suami',
                    'Istri',
                    'Anak',
                    'Orang Tua',
                    'Menantu',
                    'Cucu',
                    'Famili Lain',
                    'Lainnya',
                ]),
            ],
            'golongan_darah' => [
                'nullable',
                Rule::in(['A', 'B', 'AB', 'O', 'Tidak Tahu']),
            ],
            'alamat' => [
                'required',
                'string',
                'max:1000',
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
            'kewarganegaraan' => [
                'required',
                Rule::in(['WNI', 'WNA']),
            ],
            'no_hp' => [
                'nullable',
                'string',
                'max:20',
            ],
            'disabilitas' => [
                'required',
                Rule::in(['Tidak', 'Ya']),
            ],
            'nama_ayah' => [
                'nullable',
                'string',
                'max:255',
            ],
            'nama_ibu' => [
                'nullable',
                'string',
                'max:255',
            ],
            'tanggal_mutasi' => [
                'required',
                'date',
            ],
            'alamat_asal' => [
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
            'email' => 'email',
            'password' => 'password',
            'password_confirmation' => 'konfirmasi password',
            'nik' => 'NIK',
            'no_kk' => 'nomor KK',
            'nama' => 'nama lengkap',
            'tempat_lahir' => 'tempat lahir',
            'tanggal_lahir' => 'tanggal lahir',
            'jenis_kelamin' => 'jenis kelamin',
            'agama' => 'agama',
            'status_perkawinan' => 'status perkawinan',
            'pendidikan' => 'pendidikan terakhir',
            'pekerjaan' => 'pekerjaan',
            'status_hubungan_keluarga' => 'status hubungan dalam keluarga',
            'golongan_darah' => 'golongan darah',
            'alamat' => 'alamat',
            'rt' => 'RT',
            'rw' => 'RW',
            'dusun' => 'dusun',
            'kewarganegaraan' => 'kewarganegaraan',
            'no_hp' => 'nomor HP / WhatsApp',
            'disabilitas' => 'penyandang disabilitas',
            'nama_ayah' => 'nama ayah',
            'nama_ibu' => 'nama ibu',
            'tanggal_mutasi' => 'tanggal mutasi',
            'alamat_asal' => 'alamat asal',
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

        app(MutasiPendudukService::class)->mutasiMasuk([
            'nama' => $this->nama,
            'email' => $this->email,
            'password' => $this->password,
            'nik' => $this->nik,
            'no_kk' => $this->no_kk,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'jenis_kelamin' => $this->jenis_kelamin,
            'agama' => $this->agama,
            'status_perkawinan' => $this->status_perkawinan,
            'pendidikan' => $this->pendidikan,
            'pekerjaan' => $this->pekerjaan,
            'status_hubungan_keluarga' => $this->status_hubungan_keluarga,
            'golongan_darah' => $this->golongan_darah,
            'alamat' => $this->alamat,
            'rt' => $this->rt,
            'rw' => $this->rw,
            'dusun' => $this->dusun,
            'kewarganegaraan' => $this->kewarganegaraan,
            'no_hp' => $this->no_hp,
            'disabilitas' => $this->disabilitas,
            'nama_ayah' => $this->nama_ayah,
            'nama_ibu' => $this->nama_ibu,
            'tanggal_mutasi' => $this->tanggal_mutasi,
            'alamat_asal' => $this->alamat_asal,
            'alasan' => $this->alasan,
            'catatan' => $this->catatan,
        ]);

        session()->flash('success', 'Mutasi masuk berhasil dicatat.');

        return $this->redirectRoute('perangkat-desa.penduduk.index');
    }

    public function render()
    {
        return view('livewire.perangkat-desa.mutasi-masuk.create');
    }
}
