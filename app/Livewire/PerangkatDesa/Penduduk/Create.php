<?php

namespace App\Livewire\PerangkatDesa\Penduduk;

use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Create extends Component
{
    use WithFileUploads;

    public $foto;
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
    public $status_kependudukan;
    public $disabilitas;
    public $nama_ayah;
    public $nama_ibu;

    protected function rules(): array
    {
        return [

            'foto' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],

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
                Rule::in([
                    'L',
                    'P',
                ]),
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
                Rule::in([
                    'A',
                    'B',
                    'AB',
                    'O',
                    'Tidak Tahu',
                ]),
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
                Rule::in([
                    'WNI',
                    'WNA',
                ]),
            ],
            'no_hp' => [
                'nullable',
                'string',
                'max:20',
            ],
            'status_kependudukan' => [
                'required',
                Rule::in([
                    'Tetap',
                    'Pendatang',
                    'Pindah',
                    'Tidak Diketahui',
                ]),
            ],
            'disabilitas' => [
                'required',
                Rule::in([
                    'Tidak',
                    'Ya',
                ]),
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
        ];
    }
    protected function validationAttributes(): array
    {
        return [
            'foto' => 'foto',
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
            'status_kependudukan' => 'status kependudukan',
            'disabilitas' => 'penyandang disabilitas',
            'nama_ayah' => 'nama ayah',
            'nama_ibu' => 'nama ibu',
        ];
    }
    public function simpan()
    {
        $validated = $this->validate();
        try {
            DB::transaction(function () use ($validated) {
                $fotoPath = null;
                if ($this->foto) {
                    $fotoPath = $this->foto->store(
                        'penduduk',
                        'public'
                    );
                }
                $user = User::create([
                    'name' => $this->nama,
                    'email' => $this->email,
                    'password' => $this->password,
                    'role' => 'masyarakat',
                ]);
                Penduduk::create([
                    'user_id' => $user->id,
                    'foto' => $fotoPath,
                    'nik' => $this->nik,
                    'no_kk' => $this->no_kk,
                    'nama' => $this->nama,
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
                    'status_kependudukan' => $this->status_kependudukan,
                    'disabilitas' => $this->disabilitas === 'Ya',
                    'nama_ayah' => $this->nama_ayah,
                    'nama_ibu' => $this->nama_ibu,
                ]);
            });
            session()->flash(
                'success',
                'Data penduduk dan akun SIDESKA berhasil dibuat.'
            );

            return $this->redirectRoute(
                'perangkat-desa.penduduk.index',
                navigate: true
            );
        } catch (\Throwable $e) {
            report($e);
            session()->flash(
                'error',
                'Data penduduk gagal disimpan. Silakan coba lagi.'
            );

            return null;
        }
    }

    public function render()
    {
        return view(
            'livewire.perangkat-desa.penduduk.create'
        );
    }
}
