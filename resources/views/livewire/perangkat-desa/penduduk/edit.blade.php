<div class="w-full" x-data
    x-on:update-success.window="
        setTimeout(() => {
            window.location.href = '{{ route('perangkat-desa.penduduk.index') }}';
        }, 1500)
">
    <div class="mb-6">
        <div class="flex flex-col gap-1">
            <h1 class="text-xl font-bold text-slate-800">
                Edit Penduduk
            </h1>

            <p class="text-sm text-slate-500">
                Perbarui data penduduk beserta akun SIDESKA.
                Data ini akan digunakan untuk pelayanan administrasi desa.
            </p>
        </div>
    </div>
    @if (session()->has('success'))
        <div
            class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200
                   bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="mb-6 overflow-hidden rounded-2xl border border-slate-200
               bg-white px-6 py-5 shadow-sm">
        <div class="flex items-center">
            <div class="flex flex-1 items-center">
                <div class="flex flex-col items-center">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-full
                               bg-blue-600 text-sm font-bold text-white">
                        1
                    </div>

                    <span class="mt-2 whitespace-nowrap text-xs font-semibold text-blue-600">
                        Foto Penduduk
                    </span>
                </div>

                <div class="mx-4 h-0.5 flex-1 bg-blue-200"></div>
            </div>
            <div class="flex flex-1 items-center">
                <div class="flex flex-col items-center">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-full
                               bg-blue-600 text-sm font-bold text-white">
                        2
                    </div>
                    <span class="mt-2 whitespace-nowrap text-xs font-semibold text-blue-600">
                        Akun SIDESKA
                    </span>
                </div>
                <div class="mx-4 h-0.5 flex-1 bg-blue-200"></div>
            </div>

            <div class="flex flex-1 items-center">
                <div class="flex flex-col items-center">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-full
                               bg-blue-600 text-sm font-bold text-white">
                        3
                    </div>
                    <span class="mt-2 whitespace-nowrap text-xs font-semibold text-blue-600">
                        Identitas
                    </span>
                </div>
                <div class="mx-4 h-0.5 flex-1 bg-blue-200"></div>
            </div>

            <div class="flex flex-col items-center">
                <div
                    class="flex h-9 w-9 items-center justify-center rounded-full
                           bg-blue-600 text-sm font-bold text-white">
                    4
                </div>
                <span class="mt-2 whitespace-nowrap text-xs font-semibold text-blue-600">
                    Alamat & Data Tambahan
                </span>
            </div>
        </div>
    </div>
    <form wire:submit.prevent="update" class="space-y-6">
        <div class="overflow-hidden rounded-2xl border border-slate-200
                   bg-white shadow-sm">
            <div class="grid grid-cols-1 lg:grid-cols-2">
                <div class="border-b border-slate-200 p-6 lg:border-b-0 lg:border-r">
                    <div class="mb-6 flex items-center gap-3">
                        <div
                            class="flex h-9 w-9 shrink-0 items-center justify-center
                                   rounded-lg bg-blue-600">
                            <span class="text-sm font-bold text-white">
                                1
                            </span>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800">
                                Foto Penduduk
                            </h2>
                            <p class="text-xs text-slate-500">
                                Perbarui foto penduduk
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start gap-5">
                        <div
                            class="flex h-32 w-28 shrink-0 items-center justify-center
               overflow-hidden rounded-xl border-2 border-dashed
               border-slate-300 bg-slate-50">
                            @if ($foto)
                                <img src="{{ $foto->temporaryUrl() }}" alt="Preview Foto Penduduk"
                                    class="h-full w-full object-cover">
                            @elseif ($foto_lama)
                                <img src="{{ asset('storage/' . $foto_lama) }}" alt="Foto Penduduk"
                                    class="h-full w-full object-cover">
                            @else
                                <div class="text-center">
                                    <svg class="mx-auto h-10 w-10 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 11-7.5 0
                           3.75 3.75 0 017.5 0z
                           M4.5 20.25a8.25 8.25 0 0115 0" />
                                    </svg>
                                    <span class="mt-1 block text-xs text-slate-400">
                                        Foto
                                    </span>
                                </div>
                            @endif

                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm font-semibold text-slate-800">
                                Upload Foto Penduduk
                            </h3>
                            <p class="mt-1 max-w-xs text-xs leading-5 text-slate-500">
                                Pilih foto baru jika ingin mengganti foto
                                penduduk yang sudah tersimpan.
                            </p>
                            <label for="foto"
                                class="mt-4 inline-flex cursor-pointer items-center gap-2
                   rounded-lg bg-blue-600 px-4 py-2.5
                   text-sm font-semibold text-white
                   transition hover:bg-blue-700
                   focus-within:ring-2 focus-within:ring-blue-500
                   focus-within:ring-offset-2">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16V4m0 0L8 8m4-4l4 4
                       M4 16v3a1 1 0 001 1h14a1 1 0 001-1v-3" />
                                </svg>
                                Ganti Foto
                            </label>
                            <input id="foto" type="file" wire:model="foto" accept="image/jpeg,image/png"
                                class="hidden">
                            <p class="mt-2 text-xs text-slate-400">
                                JPG atau PNG. Maksimal 2 MB.
                            </p>
                            <div wire:loading wire:target="foto" class="mt-2 text-xs font-medium text-blue-600">
                                Mengupload foto...
                            </div>
                            @error('foto')
                                <p class="mt-2 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="mb-6 flex items-center gap-3">
                        <div
                            class="flex h-9 w-9 shrink-0 items-center justify-center
                                   rounded-lg bg-blue-600">
                            <span class="text-sm font-bold text-white">
                                2
                            </span>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800">
                                Akun SIDESKA
                            </h2>
                            <p class="text-xs text-slate-500">
                                Perbarui akun login penduduk
                            </p>
                        </div>
                    </div>
                    <div class="mb-5">
                        <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">
                            Email
                            <span class="text-red-500">*</span>
                        </label>
                        <input id="email" type="email" wire:model="email" autocomplete="email"
                            placeholder="contoh@email.com"
                            class="w-full rounded-lg border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-700
                                   outline-none placeholder:text-slate-400
                                   transition focus:border-blue-500
                                   focus:ring-2 focus:ring-blue-100">
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="mb-5">
                        <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">
                            Password Baru
                        </label>
                        <div x-data="{ showPassword: false }" class="relative">
                            <input id="password" wire:model="password" autocomplete="new-password"
                                placeholder="Kosongkan jika tidak diubah" :type="showPassword ? 'text' : 'password'"
                                class="w-full rounded-lg border border-slate-300
                                       bg-white px-4 py-3 pr-11 text-sm
                                       text-slate-700 outline-none
                                       placeholder:text-slate-400
                                       transition focus:border-blue-500
                                       focus:ring-2 focus:ring-blue-100">
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute right-3 top-1/2
                                       -translate-y-1/2 text-slate-400
                                       transition hover:text-slate-600"
                                :aria-label="showPassword
                                    ?
                                    'Sembunyikan password' :
                                    'Tampilkan password'">

                                <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 12s3.75-6 9.75-6 9.75 6
                                           9.75 6-3.75 6-9.75 6-9.75-6-9.75-6z" />

                                    <circle cx="12" cy="12" r="3" stroke-width="1.5" />
                                </svg>
                                <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3l18 18
                                           M10.58 10.58a2 2 0 002.84 2.84
                                           M9.88 5.09A9.77 9.77 0 0112 4.75
                                           c6 0 9.75 6 9.75 6a16.9 16.9 0 01-3.05
                                           3.95
                                           M6.61 6.61C3.88 8.39 2.25 10.75
                                           2.25 10.75s3.75 6 9.75 6
                                           c1.16 0 2.22-.2 3.17-.53" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="mb-5">
                        <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">
                            Konfirmasi Password Baru
                        </label>
                        <div x-data="{ showConfirmation: false }" class="relative">
                            <input id="password_confirmation" wire:model="password_confirmation"
                                autocomplete="new-password" placeholder="Ulangi password baru"
                                :type="showConfirmation ? 'text' : 'password'"
                                class="w-full rounded-lg border border-slate-300
                                       bg-white px-4 py-3 pr-11 text-sm
                                       text-slate-700 outline-none
                                       placeholder:text-slate-400
                                       transition focus:border-blue-500
                                       focus:ring-2 focus:ring-blue-100">
                            <button type="button" @click="showConfirmation = !showConfirmation"
                                class="absolute right-3 top-1/2
                                       -translate-y-1/2 text-slate-400
                                       transition hover:text-slate-600"
                                :aria-label="showConfirmation
                                    ?
                                    'Sembunyikan password' :
                                    'Tampilkan password'">

                                <svg x-show="!showConfirmation" class="h-5 w-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 12s3.75-6 9.75-6 9.75 6
                                           9.75 6-3.75 6-9.75 6-9.75-6-9.75-6z" />

                                    <circle cx="12" cy="12" r="3" stroke-width="1.5" />
                                </svg>
                                <svg x-show="showConfirmation" x-cloak class="h-5 w-5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3l18 18
                                           M10.58 10.58a2 2 0 002.84 2.84
                                           M9.88 5.09A9.77 9.77 0 0112 4.75
                                           c6 0 9.75 6 9.75 6a16.9 16.9 0 01-3.05
                                           3.95
                                           M6.61 6.61C3.88 8.39 2.25 10.75
                                           2.25 10.75s3.75 6 9.75 6
                                           c1.16 0 2.22-.2 3.17-.53" />
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div
                        class="flex gap-3 rounded-xl border border-blue-100
                               bg-blue-50 p-4">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-blue-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9" stroke-width="1.5" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 10v6m0-9h.01" />
                        </svg>
                        <p class="text-xs leading-5 text-slate-600">
                            Password hanya perlu diisi jika ingin mengganti
                            password akun penduduk. Jika dikosongkan,
                            password lama tetap digunakan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="overflow-hidden rounded-2xl border border-slate-200
                   bg-white shadow-sm">
            <div class="grid grid-cols-1 lg:grid-cols-2">
                <div class="border-b border-slate-200 p-6 lg:border-b-0 lg:border-r">
                    <div class="mb-6 flex items-center gap-3">
                        <div
                            class="flex h-9 w-9 shrink-0 items-center justify-center
                                   rounded-lg bg-blue-600">
                            <span class="text-sm font-bold text-white">
                                3
                            </span>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800">
                                Identitas Penduduk
                            </h2>
                            <p class="text-xs text-slate-500">
                                Data identitas resmi penduduk
                            </p>
                        </div>
                    </div>
                    <div class="mb-5">
                        <label for="nik" class="mb-2 block text-sm font-semibold text-slate-700">
                            NIK
                            <span class="text-red-500">*</span>
                        </label>
                        <input id="nik" type="text" wire:model="nik" maxlength="16" inputmode="numeric"
                            placeholder="Masukkan NIK 16 digit"
                            class="w-full rounded-lg border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-700
                                   outline-none placeholder:text-slate-400
                                   transition focus:border-blue-500
                                   focus:ring-2 focus:ring-blue-100">

                        @error('nik')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="mb-5">
                        <label for="no_kk" class="mb-2 block text-sm font-semibold text-slate-700">
                            Nomor KK
                            <span class="text-red-500">*</span>
                        </label>
                        <input id="no_kk" type="text" wire:model="no_kk" maxlength="16" inputmode="numeric"
                            placeholder="Masukkan nomor KK 16 digit"
                            class="w-full rounded-lg border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-700
                                   outline-none placeholder:text-slate-400
                                   transition focus:border-blue-500
                                   focus:ring-2 focus:ring-blue-100">
                        @error('no_kk')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="mb-5">
                        <label for="nama" class="mb-2 block text-sm font-semibold text-slate-700">
                            Nama Lengkap
                            <span class="text-red-500">*</span>
                        </label>
                        <input id="nama" type="text" wire:model="nama" placeholder="Masukkan nama lengkap"
                            class="w-full rounded-lg border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-700
                                   outline-none placeholder:text-slate-400
                                   transition focus:border-blue-500
                                   focus:ring-2 focus:ring-blue-100">

                        @error('nama')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="mb-5">
                        <label for="tempat_lahir" class="mb-2 block text-sm font-semibold text-slate-700">
                            Tempat Lahir
                            <span class="text-red-500">*</span>
                        </label>
                        <input id="tempat_lahir" type="text" wire:model="tempat_lahir"
                            placeholder="Masukkan tempat lahir"
                            class="w-full rounded-lg border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-700
                                   outline-none placeholder:text-slate-400
                                   transition focus:border-blue-500
                                   focus:ring-2 focus:ring-blue-100">
                        @error('tempat_lahir')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="mb-5">
                        <label for="tanggal_lahir" class="mb-2 block text-sm font-semibold text-slate-700">
                            Tanggal Lahir
                            <span class="text-red-500">*</span>
                        </label>
                        <input id="tanggal_lahir" type="date" wire:model="tanggal_lahir"
                            class="w-full rounded-lg border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-700
                                   outline-none transition focus:border-blue-500
                                   focus:ring-2 focus:ring-blue-100">
                        @error('tanggal_lahir')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="mb-5">
                        <label for="jenis_kelamin" class="mb-2 block text-sm font-semibold text-slate-700">
                            Jenis Kelamin
                            <span class="text-red-500">*</span>
                        </label>
                        <select id="jenis_kelamin" wire:model="jenis_kelamin"
                            class="w-full rounded-lg border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-700
                                   outline-none transition focus:border-blue-500
                                   focus:ring-2 focus:ring-blue-100">
                            <option value="">
                                Pilih jenis kelamin
                            </option>

                            <option value="L">
                                Laki-laki
                            </option>

                            <option value="P">
                                Perempuan
                            </option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="mb-5">
                        <label for="agama" class="mb-2 block text-sm font-semibold text-slate-700">
                            Agama
                            <span class="text-red-500">*</span>
                        </label>
                        <select id="agama" wire:model="agama"
                            class="w-full rounded-lg border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-700
                                   outline-none transition focus:border-blue-500
                                   focus:ring-2 focus:ring-blue-100">
                            <option value="">
                                Pilih agama
                            </option>
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
                        @error('agama')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="mb-5">
                        <label for="status_perkawinan" class="mb-2 block text-sm font-semibold text-slate-700">
                            Status Perkawinan
                            <span class="text-red-500">*</span>
                        </label>
                        <select id="status_perkawinan" wire:model="status_perkawinan"
                            class="w-full rounded-lg border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-700
                                   outline-none transition focus:border-blue-500
                                   focus:ring-2 focus:ring-blue-100">
                            <option value="">
                                Pilih status perkawinan
                            </option>
                            <option value="Belum Kawin">
                                Belum Kawin
                            </option>
                            <option value="Kawin">
                                Kawin
                            </option>
                            <option value="Cerai Hidup">
                                Cerai Hidup
                            </option>
                            <option value="Cerai Mati">
                                Cerai Mati
                            </option>
                        </select>
                        @error('status_perkawinan')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="mb-5">
                        <label for="pendidikan" class="mb-2 block text-sm font-semibold text-slate-700">
                            Pendidikan Terakhir
                            <span class="text-red-500">*</span>
                        </label>
                        <select id="pendidikan" wire:model="pendidikan"
                            class="w-full rounded-lg border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-700
                                   outline-none transition focus:border-blue-500
                                   focus:ring-2 focus:ring-blue-100">
                            <option value="">
                                Pilih pendidikan terakhir
                            </option>

                            <option value="Tidak/Belum Sekolah">
                                Tidak/Belum Sekolah
                            </option>
                            <option value="SD/Sederajat">
                                SD/Sederajat
                            </option>
                            <option value="SMP/Sederajat">
                                SMP/Sederajat
                            </option>
                            <option value="SMA/Sederajat">
                                SMA/Sederajat
                            </option>
                            <option value="Diploma">
                                Diploma
                            </option>
                            <option value="S1">
                                S1
                            </option>
                            <option value="S2">
                                S2
                            </option>
                            <option value="S3">
                                S3
                            </option>
                        </select>
                        @error('pendidikan')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="mb-5">
                        <label for="pekerjaan" class="mb-2 block text-sm font-semibold text-slate-700">
                            Pekerjaan
                            <span class="text-red-500">*</span>
                        </label>
                        <input id="pekerjaan" type="text" wire:model="pekerjaan" placeholder="Masukkan pekerjaan"
                            class="w-full rounded-lg border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-700
                                   outline-none placeholder:text-slate-400
                                   transition focus:border-blue-500
                                   focus:ring-2 focus:ring-blue-100">
                        @error('pekerjaan')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="mb-5">
                        <label for="status_hubungan_keluarga" class="mb-2 block text-sm font-semibold text-slate-700">
                            Status Hubungan Dalam Keluarga
                            <span class="text-red-500">*</span>
                        </label>
                        <select id="status_hubungan_keluarga" wire:model="status_hubungan_keluarga"
                            class="w-full rounded-lg border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-700
                                   outline-none transition focus:border-blue-500
                                   focus:ring-2 focus:ring-blue-100">
                            <option value="">
                                Pilih status dalam keluarga
                            </option>
                            <option value="Kepala Keluarga">
                                Kepala Keluarga
                            </option>
                            <option value="Suami">
                                Suami
                            </option>
                            <option value="Istri">
                                Istri
                            </option>
                            <option value="Anak">
                                Anak
                            </option>
                            <option value="Orang Tua">
                                Orang Tua
                            </option>
                            <option value="Menantu">
                                Menantu
                            </option>
                            <option value="Cucu">
                                Cucu
                            </option>
                            <option value="Famili Lain">
                                Famili Lain
                            </option>
                            <option value="Lainnya">
                                Lainnya
                            </option>
                        </select>
                        @error('status_hubungan_keluarga')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="mb-5">
                        <label for="golongan_darah" class="mb-2 block text-sm font-semibold text-slate-700">
                            Golongan Darah
                        </label>
                        <select id="golongan_darah" wire:model="golongan_darah"
                            class="w-full rounded-lg border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-700
                                   outline-none transition focus:border-blue-500
                                   focus:ring-2 focus:ring-blue-100">
                            <option value="">
                                Pilih golongan darah
                            </option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="AB">AB</option>
                            <option value="O">O</option>
                            <option value="Tidak Tahu">
                                Tidak Tahu
                            </option>
                        </select>
                        @error('golongan_darah')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
                <div class="p-6">
                    <div class="mb-6 flex items-center gap-3">
                        <div
                            class="flex h-9 w-9 shrink-0 items-center justify-center
                                   rounded-lg bg-blue-600">
                            <span class="text-sm font-bold text-white">
                                4
                            </span>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800">
                                Alamat & Data Tambahan
                            </h2>
                            <p class="text-xs text-slate-500">
                                Data tempat tinggal dan informasi pendukung
                            </p>
                        </div>
                    </div>
                    <div class="mb-5">
                        <label for="alamat" class="mb-2 block text-sm font-semibold text-slate-700">
                            Alamat
                            <span class="text-red-500">*</span>
                        </label>
                        <textarea id="alamat" wire:model="alamat" rows="4" placeholder="Masukkan alamat lengkap"
                            class="w-full resize-none rounded-lg border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-700
                                   outline-none placeholder:text-slate-400
                                   transition focus:border-blue-500
                                   focus:ring-2 focus:ring-blue-100"></textarea>
                        @error('alamat')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="rt" class="mb-2 block text-sm font-semibold text-slate-700">
                                RT
                                <span class="text-red-500">*</span>
                            </label>

                            <input id="rt" type="text" wire:model="rt" maxlength="3" inputmode="numeric"
                                placeholder="001"
                                class="w-full rounded-lg border border-slate-300
                                       bg-white px-4 py-3 text-sm text-slate-700
                                       outline-none placeholder:text-slate-400
                                       transition focus:border-blue-500
                                       focus:ring-2 focus:ring-blue-100">

                            @error('rt')
                                <p class="mt-1.5 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>
                        <div>
                            <label for="rw" class="mb-2 block text-sm font-semibold text-slate-700">
                                RW
                                <span class="text-red-500">*</span>
                            </label>

                            <input id="rw" type="text" wire:model="rw" maxlength="3" inputmode="numeric"
                                placeholder="001"
                                class="w-full rounded-lg border border-slate-300
                                       bg-white px-4 py-3 text-sm text-slate-700
                                       outline-none placeholder:text-slate-400
                                       transition focus:border-blue-500
                                       focus:ring-2 focus:ring-blue-100">

                            @error('rw')
                                <p class="mt-1.5 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>


                    {{-- DUSUN --}}
                    <div class="mb-5">

                        <label for="dusun" class="mb-2 block text-sm font-semibold text-slate-700">
                            Dusun
                            <span class="text-red-500">*</span>
                        </label>

                        <input id="dusun" type="text" wire:model="dusun" placeholder="Masukkan nama dusun"
                            class="w-full rounded-lg border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-700
                                   outline-none placeholder:text-slate-400
                                   transition focus:border-blue-500
                                   focus:ring-2 focus:ring-blue-100">

                        @error('dusun')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- KEWARGANEGARAAN --}}
                    <div class="mb-5">

                        <label for="kewarganegaraan" class="mb-2 block text-sm font-semibold text-slate-700">
                            Kewarganegaraan
                            <span class="text-red-500">*</span>
                        </label>

                        <select id="kewarganegaraan" wire:model="kewarganegaraan"
                            class="w-full rounded-lg border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-700
                                   outline-none transition focus:border-blue-500
                                   focus:ring-2 focus:ring-blue-100">
                            <option value="">
                                Pilih kewarganegaraan
                            </option>

                            <option value="WNI">
                                WNI
                            </option>

                            <option value="WNA">
                                WNA
                            </option>
                        </select>

                        @error('kewarganegaraan')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- NOMOR HP --}}
                    <div class="mb-5">

                        <label for="no_hp" class="mb-2 block text-sm font-semibold text-slate-700">
                            Nomor HP / WhatsApp
                        </label>

                        <input id="no_hp" type="text" wire:model="no_hp" maxlength="20" inputmode="tel"
                            placeholder="Contoh: 081234567890"
                            class="w-full rounded-lg border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-700
                                   outline-none placeholder:text-slate-400
                                   transition focus:border-blue-500
                                   focus:ring-2 focus:ring-blue-100">

                        @error('no_hp')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- STATUS KEPENDUDUKAN --}}
                    <div class="mb-5">

                        <label for="status_kependudukan" class="mb-2 block text-sm font-semibold text-slate-700">
                            Status Kependudukan
                            <span class="text-red-500">*</span>
                        </label>

                        <select id="status_kependudukan" wire:model="status_kependudukan"
                            class="w-full rounded-lg border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-700
                                   outline-none transition focus:border-blue-500
                                   focus:ring-2 focus:ring-blue-100">
                            <option value="">
                                Pilih status kependudukan
                            </option>

                            <option value="Tetap">
                                Penduduk Tetap
                            </option>

                            <option value="Pendatang">
                                Penduduk Pendatang
                            </option>

                            <option value="Pindah">
                                Pindah
                            </option>

                            <option value="Tidak Diketahui">
                                Tidak Diketahui
                            </option>
                        </select>

                        @error('status_kependudukan')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- DISABILITAS --}}
                    <div class="mb-5">

                        <label for="disabilitas" class="mb-2 block text-sm font-semibold text-slate-700">
                            Penyandang Disabilitas
                            <span class="text-red-500">*</span>
                        </label>

                        <select id="disabilitas" wire:model="disabilitas"
                            class="w-full rounded-lg border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-700
                                   outline-none transition focus:border-blue-500
                                   focus:ring-2 focus:ring-blue-100">
                            <option value="">
                                Pilih
                            </option>

                            <option value="Tidak">
                                Tidak
                            </option>

                            <option value="Ya">
                                Ya
                            </option>
                        </select>

                        @error('disabilitas')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- NAMA AYAH --}}
                    <div class="mb-5">

                        <label for="nama_ayah" class="mb-2 block text-sm font-semibold text-slate-700">
                            Nama Ayah
                        </label>

                        <input id="nama_ayah" type="text" wire:model="nama_ayah" placeholder="Masukkan nama ayah"
                            class="w-full rounded-lg border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-700
                                   outline-none placeholder:text-slate-400
                                   transition focus:border-blue-500
                                   focus:ring-2 focus:ring-blue-100">

                        @error('nama_ayah')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- NAMA IBU --}}
                    <div class="mb-5">

                        <label for="nama_ibu" class="mb-2 block text-sm font-semibold text-slate-700">
                            Nama Ibu
                        </label>

                        <input id="nama_ibu" type="text" wire:model="nama_ibu" placeholder="Masukkan nama ibu"
                            class="w-full rounded-lg border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-700
                                   outline-none placeholder:text-slate-400
                                   transition focus:border-blue-500
                                   focus:ring-2 focus:ring-blue-100">

                        @error('nama_ibu')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- INFO DATA --}}
                    <div class="rounded-xl border border-slate-200
                               bg-slate-50 p-4">

                        <div class="flex gap-3">

                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-slate-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="9" stroke-width="1.5" />

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 10v6m0-9h.01" />
                            </svg>

                            <p class="text-xs leading-5 text-slate-500">
                                Pastikan data penduduk diperbarui sesuai
                                dengan dokumen kependudukan. Data ini nantinya
                                dapat digunakan otomatis dalam pembuatan surat desa.
                            </p>

                        </div>

                    </div>

                </div>

            </div>
        </div>
        <div class="rounded-2xl border border-blue-100
                   bg-blue-50 px-5 py-4">
            <div class="flex gap-3">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-blue-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01
                           M12 21a9 9 0 100-18 9 9 0 000 18z" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-blue-800">
                        Periksa kembali perubahan data
                    </p>
                    <p class="mt-1 text-xs leading-5 text-blue-700">
                        Pastikan NIK, Nomor KK, nama, tanggal lahir,
                        alamat, dan data lainnya sudah sesuai sebelum
                        menyimpan perubahan.
                    </p>

                </div>

            </div>

        </div>
        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center
                   sm:justify-between">
            <a href="{{ route('perangkat-desa.penduduk.index') }}" wire:navigate
                class="inline-flex items-center justify-center gap-2
                       rounded-lg border border-slate-300 bg-white
                       px-5 py-2.5 text-sm font-semibold text-slate-700
                       transition hover:bg-slate-50
                       focus:outline-none focus:ring-2
                       focus:ring-slate-300 focus:ring-offset-2">

                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>

                Batal

            </a>


            {{-- SIMPAN PERUBAHAN --}}
            <button type="submit" wire:loading.attr="disabled" wire:target="update"
                class="inline-flex items-center justify-center gap-2
                       rounded-lg bg-blue-600 px-6 py-2.5
                       text-sm font-semibold text-white
                       transition hover:bg-blue-700
                       focus:outline-none focus:ring-2
                       focus:ring-blue-500 focus:ring-offset-2
                       disabled:cursor-not-allowed
                       disabled:opacity-50">

                {{-- NORMAL --}}
                <span wire:loading.remove wire:target="update" class="inline-flex items-center gap-2">

                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 12h14m-6-6l6 6-6 6" />
                    </svg>

                    Simpan Perubahan

                </span>


                {{-- LOADING --}}
                <span wire:loading wire:target="update" class="inline-flex items-center gap-2">

                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4" />

                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                    </svg>

                    Menyimpan...

                </span>

            </button>

        </div>

    </form>

</div>
