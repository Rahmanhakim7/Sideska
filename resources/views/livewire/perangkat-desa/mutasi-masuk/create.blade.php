<div class="w-full">
    <div class="mb-6">
        <div class="flex flex-col gap-1">
            <h1 class="text-xl font-bold text-slate-800">
                Mutasi Masuk
            </h1>
            <p class="text-sm text-slate-500">
                Catat penduduk yang masuk ke wilayah Desa Sekartejo beserta
                akun SIDESKA-nya.
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

    <div class="mb-6 flex gap-3 rounded-xl border border-blue-100 bg-blue-50 p-4">
        <svg class="mt-0.5 h-5 w-5 shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="9" stroke-width="1.5" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 10v6m0-9h.01" />
        </svg>

        <p class="text-xs leading-5 text-slate-600">
            Penduduk hasil mutasi masuk otomatis berstatus
            <span class="font-semibold text-blue-700">Pendatang</span>.
            Alamat tujuan dicatat otomatis sebagai
            <span class="font-semibold text-blue-700">{{ \App\Services\MutasiPendudukService::WILAYAH }}</span>.
        </p>
    </div>

    <form wire:submit.prevent="simpan" class="space-y-6">
        {{-- AKUN --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-base font-bold text-slate-800">Akun SIDESKA</h2>
                <p class="text-xs text-slate-500">Akun login untuk penduduk (role Masyarakat)</p>
            </div>

            <div class="grid grid-cols-1 gap-5 p-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input id="email" type="email" wire:model="email" autocomplete="email"
                        placeholder="contoh@email.com"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none placeholder:text-slate-400 transition
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input id="password" type="password" wire:model="password" autocomplete="new-password"
                        placeholder="Minimal 8 karakter"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none placeholder:text-slate-400 transition
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <input id="password_confirmation" type="password" wire:model="password_confirmation"
                        autocomplete="new-password" placeholder="Ulangi password"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none placeholder:text-slate-400 transition
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    @error('password_confirmation')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- IDENTITAS --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-base font-bold text-slate-800">Identitas Penduduk</h2>
                <p class="text-xs text-slate-500">Data identitas resmi penduduk</p>
            </div>

            <div class="grid grid-cols-1 gap-5 p-6 sm:grid-cols-2">
                <div>
                    <label for="nik" class="mb-2 block text-sm font-semibold text-slate-700">
                        NIK <span class="text-red-500">*</span>
                    </label>
                    <input id="nik" type="text" wire:model="nik" maxlength="16" inputmode="numeric"
                        placeholder="16 digit"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none placeholder:text-slate-400 transition
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    @error('nik')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="no_kk" class="mb-2 block text-sm font-semibold text-slate-700">
                        Nomor KK <span class="text-red-500">*</span>
                    </label>
                    <input id="no_kk" type="text" wire:model="no_kk" maxlength="16" inputmode="numeric"
                        placeholder="16 digit"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none placeholder:text-slate-400 transition
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    @error('no_kk')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="nama" class="mb-2 block text-sm font-semibold text-slate-700">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input id="nama" type="text" wire:model="nama" placeholder="Nama lengkap"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none placeholder:text-slate-400 transition
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    @error('nama')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tempat_lahir" class="mb-2 block text-sm font-semibold text-slate-700">
                        Tempat Lahir <span class="text-red-500">*</span>
                    </label>
                    <input id="tempat_lahir" type="text" wire:model="tempat_lahir" placeholder="Tempat lahir"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none placeholder:text-slate-400 transition
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    @error('tempat_lahir')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tanggal_lahir" class="mb-2 block text-sm font-semibold text-slate-700">
                        Tanggal Lahir <span class="text-red-500">*</span>
                    </label>
                    <input id="tanggal_lahir" type="date" wire:model="tanggal_lahir"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none transition focus:border-blue-500
                               focus:ring-2 focus:ring-blue-100">
                    @error('tanggal_lahir')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jenis_kelamin" class="mb-2 block text-sm font-semibold text-slate-700">
                        Jenis Kelamin <span class="text-red-500">*</span>
                    </label>
                    <select id="jenis_kelamin" wire:model="jenis_kelamin"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none transition focus:border-blue-500
                               focus:ring-2 focus:ring-blue-100">
                        <option value="">Pilih jenis kelamin</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="agama" class="mb-2 block text-sm font-semibold text-slate-700">
                        Agama <span class="text-red-500">*</span>
                    </label>
                    <select id="agama" wire:model="agama"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none transition focus:border-blue-500
                               focus:ring-2 focus:ring-blue-100">
                        <option value="">Pilih agama</option>
                        <option value="Islam">Islam</option>
                        <option value="Kristen">Kristen</option>
                        <option value="Katolik">Katolik</option>
                        <option value="Hindu">Hindu</option>
                        <option value="Buddha">Buddha</option>
                        <option value="Konghucu">Konghucu</option>
                    </select>
                    @error('agama')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status_perkawinan" class="mb-2 block text-sm font-semibold text-slate-700">
                        Status Perkawinan <span class="text-red-500">*</span>
                    </label>
                    <select id="status_perkawinan" wire:model="status_perkawinan"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none transition focus:border-blue-500
                               focus:ring-2 focus:ring-blue-100">
                        <option value="">Pilih status perkawinan</option>
                        <option value="Belum Kawin">Belum Kawin</option>
                        <option value="Kawin">Kawin</option>
                        <option value="Cerai Hidup">Cerai Hidup</option>
                        <option value="Cerai Mati">Cerai Mati</option>
                    </select>
                    @error('status_perkawinan')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="pendidikan" class="mb-2 block text-sm font-semibold text-slate-700">
                        Pendidikan <span class="text-red-500">*</span>
                    </label>
                    <select id="pendidikan" wire:model="pendidikan"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none transition focus:border-blue-500
                               focus:ring-2 focus:ring-blue-100">
                        <option value="">Pilih pendidikan</option>
                        <option value="Tidak/Belum Sekolah">Tidak/Belum Sekolah</option>
                        <option value="SD/Sederajat">SD/Sederajat</option>
                        <option value="SMP/Sederajat">SMP/Sederajat</option>
                        <option value="SMA/Sederajat">SMA/Sederajat</option>
                        <option value="Diploma">Diploma</option>
                        <option value="S1">S1</option>
                        <option value="S2">S2</option>
                        <option value="S3">S3</option>
                    </select>
                    @error('pendidikan')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="pekerjaan" class="mb-2 block text-sm font-semibold text-slate-700">
                        Pekerjaan <span class="text-red-500">*</span>
                    </label>
                    <input id="pekerjaan" type="text" wire:model="pekerjaan" placeholder="Pekerjaan"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none placeholder:text-slate-400 transition
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    @error('pekerjaan')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status_hubungan_keluarga" class="mb-2 block text-sm font-semibold text-slate-700">
                        Status Hubungan Keluarga <span class="text-red-500">*</span>
                    </label>
                    <select id="status_hubungan_keluarga" wire:model="status_hubungan_keluarga"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none transition focus:border-blue-500
                               focus:ring-2 focus:ring-blue-100">
                        <option value="">Pilih status dalam keluarga</option>
                        <option value="Kepala Keluarga">Kepala Keluarga</option>
                        <option value="Suami">Suami</option>
                        <option value="Istri">Istri</option>
                        <option value="Anak">Anak</option>
                        <option value="Orang Tua">Orang Tua</option>
                        <option value="Menantu">Menantu</option>
                        <option value="Cucu">Cucu</option>
                        <option value="Famili Lain">Famili Lain</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                    @error('status_hubungan_keluarga')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="golongan_darah" class="mb-2 block text-sm font-semibold text-slate-700">
                        Golongan Darah
                    </label>
                    <select id="golongan_darah" wire:model="golongan_darah"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none transition focus:border-blue-500
                               focus:ring-2 focus:ring-blue-100">
                        <option value="">Pilih golongan darah</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="AB">AB</option>
                        <option value="O">O</option>
                        <option value="Tidak Tahu">Tidak Tahu</option>
                    </select>
                    @error('golongan_darah')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="disabilitas" class="mb-2 block text-sm font-semibold text-slate-700">
                        Penyandang Disabilitas <span class="text-red-500">*</span>
                    </label>
                    <select id="disabilitas" wire:model="disabilitas"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none transition focus:border-blue-500
                               focus:ring-2 focus:ring-blue-100">
                        <option value="">Pilih</option>
                        <option value="Tidak">Tidak</option>
                        <option value="Ya">Ya</option>
                    </select>
                    @error('disabilitas')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nama_ayah" class="mb-2 block text-sm font-semibold text-slate-700">
                        Nama Ayah
                    </label>
                    <input id="nama_ayah" type="text" wire:model="nama_ayah" placeholder="Nama ayah"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none placeholder:text-slate-400 transition
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    @error('nama_ayah')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nama_ibu" class="mb-2 block text-sm font-semibold text-slate-700">
                        Nama Ibu
                    </label>
                    <input id="nama_ibu" type="text" wire:model="nama_ibu" placeholder="Nama ibu"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none placeholder:text-slate-400 transition
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    @error('nama_ibu')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- ALAMAT TEMPAT TINGGAL BARU --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-base font-bold text-slate-800">Alamat Tempat Tinggal di Desa Sekartejo</h2>
                <p class="text-xs text-slate-500">
                    Alamat penduduk setelah masuk ke wilayah desa
                </p>
            </div>

            <div class="grid grid-cols-1 gap-5 p-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="alamat" class="mb-2 block text-sm font-semibold text-slate-700">
                        Alamat <span class="text-red-500">*</span>
                    </label>
                    <textarea id="alamat" wire:model="alamat" rows="3" placeholder="Alamat lengkap"
                        class="w-full resize-none rounded-lg border border-slate-300 bg-white px-4 py-3
                               text-sm text-slate-700 outline-none placeholder:text-slate-400
                               transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"></textarea>
                    @error('alamat')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="rt" class="mb-2 block text-sm font-semibold text-slate-700">
                        RT <span class="text-red-500">*</span>
                    </label>
                    <input id="rt" type="text" wire:model="rt" maxlength="3" inputmode="numeric"
                        placeholder="001"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none placeholder:text-slate-400 transition
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    @error('rt')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="rw" class="mb-2 block text-sm font-semibold text-slate-700">
                        RW <span class="text-red-500">*</span>
                    </label>
                    <input id="rw" type="text" wire:model="rw" maxlength="3" inputmode="numeric"
                        placeholder="001"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none placeholder:text-slate-400 transition
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    @error('rw')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="dusun" class="mb-2 block text-sm font-semibold text-slate-700">
                        Dusun <span class="text-red-500">*</span>
                    </label>
                    <input id="dusun" type="text" wire:model="dusun" placeholder="Nama dusun"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none placeholder:text-slate-400 transition
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    @error('dusun')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kewarganegaraan" class="mb-2 block text-sm font-semibold text-slate-700">
                        Kewarganegaraan <span class="text-red-500">*</span>
                    </label>
                    <select id="kewarganegaraan" wire:model="kewarganegaraan"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none transition focus:border-blue-500
                               focus:ring-2 focus:ring-blue-100">
                        <option value="">Pilih kewarganegaraan</option>
                        <option value="WNI">WNI</option>
                        <option value="WNA">WNA</option>
                    </select>
                    @error('kewarganegaraan')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="no_hp" class="mb-2 block text-sm font-semibold text-slate-700">
                        Nomor HP / WhatsApp
                    </label>
                    <input id="no_hp" type="text" wire:model="no_hp" maxlength="20" inputmode="tel"
                        placeholder="Contoh: 081234567890"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none placeholder:text-slate-400 transition
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    @error('no_hp')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- DATA MUTASI --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-base font-bold text-slate-800">Data Mutasi Masuk</h2>
                <p class="text-xs text-slate-500">Detail perpindahan penduduk ke Desa Sekartejo</p>
            </div>

            <div class="grid grid-cols-1 gap-5 p-6 sm:grid-cols-2">
                <div>
                    <label for="tanggal_mutasi" class="mb-2 block text-sm font-semibold text-slate-700">
                        Tanggal Mutasi <span class="text-red-500">*</span>
                    </label>
                    <input id="tanggal_mutasi" type="date" wire:model="tanggal_mutasi"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none transition focus:border-blue-500
                               focus:ring-2 focus:ring-blue-100">
                    @error('tanggal_mutasi')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="alamat_asal" class="mb-2 block text-sm font-semibold text-slate-700">
                        Alamat Asal <span class="text-red-500">*</span>
                    </label>
                    <textarea id="alamat_asal" wire:model="alamat_asal" rows="3"
                        placeholder="Alamat lengkap sebelum masuk ke Desa Sekartejo"
                        class="w-full resize-none rounded-lg border border-slate-300 bg-white px-4 py-3
                               text-sm text-slate-700 outline-none placeholder:text-slate-400 transition
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100"></textarea>
                    @error('alamat_asal')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="alasan" class="mb-2 block text-sm font-semibold text-slate-700">
                        Alasan
                    </label>
                    <input id="alasan" type="text" wire:model="alasan" placeholder="Alasan pindah masuk"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none placeholder:text-slate-400 transition
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    @error('alasan')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="catatan" class="mb-2 block text-sm font-semibold text-slate-700">
                        Catatan
                    </label>
                    <input id="catatan" type="text" wire:model="catatan" placeholder="Catatan tambahan"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none placeholder:text-slate-400 transition
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    @error('catatan')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('perangkat-desa.penduduk.index') }}" wire:navigate
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300
                       bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition
                       hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300
                       focus:ring-offset-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Batal
            </a>

            <button type="submit" wire:loading.attr="disabled" wire:target="simpan"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5
                       text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none
                       focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed
                       disabled:opacity-50">
                <span wire:loading.remove wire:target="simpan" class="inline-flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 12h14m-6-6l6 6-6 6" />
                    </svg>
                    Simpan Mutasi Masuk
                </span>

                <span wire:loading wire:target="simpan" class="inline-flex items-center gap-2">
                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                    </svg>
                    Menyimpan...
                </span>
            </button>
        </div>
    </form>
</div>
