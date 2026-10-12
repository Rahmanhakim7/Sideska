<div class="min-h-screen bg-slate-50">

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">

        <div class="mb-6">

            <h1 class="text-2xl font-bold text-slate-800">
                Surat Keterangan Pindah
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Silakan periksa data diri dan lengkapi data perpindahan serta keperluan pengajuan surat.
            </p>

        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <form wire:submit="ajukan">

                {{-- Data Pemohon --}}
                <div class="border-b border-slate-100 px-6 py-5">

                    <h2 class="text-base font-semibold text-slate-800">
                        Data Pemohon
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Data berikut diambil dari data kependudukan Anda.
                    </p>

                    @if ($modeRevisi)
                        <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3">

                            <div class="flex items-start gap-3">

                                <svg class="mt-0.5 h-5 w-5 shrink-0 text-amber-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v3.75m0 3.75h.008M10.29 3.86l-7.1 12.28A1.75 1.75 0 004.71 18.75h14.58a1.75 1.75 0 001.52-2.61l-7.1-12.28a1.75 1.75 0 00-3.04 0z" />

                                </svg>

                                <div>

                                    <p class="text-sm font-semibold text-amber-800">
                                        Perbaikan Pengajuan
                                    </p>

                                    <p class="mt-1 text-sm leading-6 text-amber-700">
                                        Silakan perbaiki data perpindahan dan keperluan, lalu
                                        kirim kembali pengajuan Anda.
                                    </p>

                                </div>

                            </div>

                        </div>
                    @endif

                </div>

                <div class="grid gap-5 px-6 py-6 sm:grid-cols-2">

                    {{-- Nama --}}
                    <div>

                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Nama
                        </label>

                        <input type="text" value="{{ $penduduk->nama }}" readonly
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5
                                   text-sm text-slate-600 focus:border-slate-200 focus:ring-0">

                    </div>

                    {{-- NIK --}}
                    <div>

                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            NIK
                        </label>

                        <input type="text" value="{{ $penduduk->nik }}" readonly
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5
                                   text-sm text-slate-600 focus:border-slate-200 focus:ring-0">

                    </div>

                    {{-- Nomor KK --}}
                    <div>

                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Nomor KK
                        </label>

                        <input type="text" value="{{ $penduduk->no_kk }}" readonly
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5
                                   text-sm text-slate-600 focus:border-slate-200 focus:ring-0">

                    </div>

                    {{-- Tempat / Tanggal Lahir --}}
                    <div>

                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Tempat / Tanggal Lahir
                        </label>

                        <input type="text"
                            value="{{ $penduduk->tempat_lahir }}, {{ $penduduk->tanggal_lahir?->format('d-m-Y') }}"
                            readonly
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5
                                   text-sm text-slate-600 focus:border-slate-200 focus:ring-0">

                    </div>

                    {{-- Jenis Kelamin --}}
                    <div>

                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Jenis Kelamin
                        </label>

                        <input type="text" value="{{ $penduduk->jenis_kelamin }}" readonly
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5
                                   text-sm text-slate-600 focus:border-slate-200 focus:ring-0">

                    </div>

                    {{-- Agama --}}
                    <div>

                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Agama
                        </label>

                        <input type="text" value="{{ $penduduk->agama }}" readonly
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5
                                   text-sm text-slate-600 focus:border-slate-200 focus:ring-0">

                    </div>

                    {{-- Pekerjaan --}}
                    <div>

                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Pekerjaan
                        </label>

                        <input type="text" value="{{ $penduduk->pekerjaan }}" readonly
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5
                                   text-sm text-slate-600 focus:border-slate-200 focus:ring-0">

                    </div>

                    {{-- Alamat Asal --}}
                    <div class="sm:col-span-2">

                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Alamat Asal
                        </label>

                        <textarea readonly rows="2"
                            class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50
                                   px-4 py-2.5 text-sm text-slate-600
                                   focus:border-slate-200 focus:ring-0">{{ $penduduk->alamat }}, RT {{ $penduduk->rt }}/RW {{ $penduduk->rw }}, {{ $penduduk->dusun }}</textarea>

                    </div>

                </div>

                {{-- Data Perpindahan --}}
                <div class="border-t border-slate-100 px-6 py-5">

                    <h2 class="text-base font-semibold text-slate-800">
                        Data Perpindahan
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Lengkapi data tujuan perpindahan Anda untuk kebutuhan Surat Keterangan Pindah.
                    </p>

                </div>

                <div class="grid gap-5 px-6 pb-6 sm:grid-cols-2">

                    {{-- Alasan Pindah --}}
                    <div class="sm:col-span-2">

                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Alasan Pindah <span class="text-red-500">*</span>
                        </label>

                        <input type="text" wire:model="alasanPindah" placeholder="Contoh: Pekerjaan, pendidikan, keluarga"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5
                                   text-sm text-slate-600
                                   placeholder:text-slate-400
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">

                        @error('alasanPindah')
                            <p class="mt-1.5 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Alamat Tujuan --}}
                    <div class="sm:col-span-2">

                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Alamat Tujuan <span class="text-red-500">*</span>
                        </label>

                        <textarea wire:model="alamatTujuan" rows="2" placeholder="Alamat lengkap tujuan pindah"
                            class="w-full resize-none rounded-xl border border-slate-200 px-4 py-2.5
                                   text-sm text-slate-600
                                   placeholder:text-slate-400
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20"></textarea>

                        @error('alamatTujuan')
                            <p class="mt-1.5 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- RT Tujuan --}}
                    <div>

                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            RT Tujuan
                        </label>

                        <input type="text" wire:model="rtTujuan" placeholder="Contoh: 001"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5
                                   text-sm text-slate-600
                                   placeholder:text-slate-400
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">

                        @error('rtTujuan')
                            <p class="mt-1.5 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- RW Tujuan --}}
                    <div>

                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            RW Tujuan
                        </label>

                        <input type="text" wire:model="rwTujuan" placeholder="Contoh: 002"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5
                                   text-sm text-slate-600
                                   placeholder:text-slate-400
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">

                        @error('rwTujuan')
                            <p class="mt-1.5 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Desa/Kelurahan Tujuan --}}
                    <div>

                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Desa/Kelurahan Tujuan <span class="text-red-500">*</span>
                        </label>

                        <input type="text" wire:model="desaKelurahanTujuan" placeholder="Contoh: Sukamaju"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5
                                   text-sm text-slate-600
                                   placeholder:text-slate-400
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">

                        @error('desaKelurahanTujuan')
                            <p class="mt-1.5 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Kecamatan Tujuan --}}
                    <div>

                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Kecamatan Tujuan <span class="text-red-500">*</span>
                        </label>

                        <input type="text" wire:model="kecamatanTujuan" placeholder="Contoh: Banyuasin"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5
                                   text-sm text-slate-600
                                   placeholder:text-slate-400
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">

                        @error('kecamatanTujuan')
                            <p class="mt-1.5 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Kabupaten/Kota Tujuan --}}
                    <div>

                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Kabupaten/Kota Tujuan <span class="text-red-500">*</span>
                        </label>

                        <input type="text" wire:model="kabupatenTujuan" placeholder="Contoh: Purworejo"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5
                                   text-sm text-slate-600
                                   placeholder:text-slate-400
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">

                        @error('kabupatenTujuan')
                            <p class="mt-1.5 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Provinsi Tujuan --}}
                    <div>

                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Provinsi Tujuan <span class="text-red-500">*</span>
                        </label>

                        <input type="text" wire:model="provinsiTujuan" placeholder="Contoh: Jawa Tengah"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5
                                   text-sm text-slate-600
                                   placeholder:text-slate-400
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">

                        @error('provinsiTujuan')
                            <p class="mt-1.5 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Klasifikasi Pindah --}}
                    <div>

                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Klasifikasi Pindah
                        </label>

                        <input type="text" wire:model="klasifikasiPindah"
                            placeholder="Contoh: Kepala Keluarga / Anggota Keluarga"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5
                                   text-sm text-slate-600
                                   placeholder:text-slate-400
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">

                        @error('klasifikasiPindah')
                            <p class="mt-1.5 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Jumlah Anggota Pindah --}}
                    <div>

                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Jumlah Anggota Pindah
                        </label>

                        <input type="number" min="1" wire:model="jumlahAnggotaPindah" placeholder="Contoh: 1"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5
                                   text-sm text-slate-600
                                   placeholder:text-slate-400
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">

                        @error('jumlahAnggotaPindah')
                            <p class="mt-1.5 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Tanggal Pindah --}}
                    <div>

                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Tanggal Pindah
                        </label>

                        <input type="date" wire:model="tanggalPindah"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5
                                   text-sm text-slate-600
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">

                        @error('tanggalPindah')
                            <p class="mt-1.5 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>

                {{-- Keperluan --}}
                <div class="border-t border-slate-100 px-6 py-5">

                    <h2 class="text-base font-semibold text-slate-800">
                        Keperluan Surat
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Jelaskan keperluan Anda mengajukan surat keterangan pindah.
                    </p>

                </div>

                <div class="px-6 pb-6">

                    <label class="mb-1.5 block text-sm font-medium text-slate-700">
                        Keperluan <span class="text-red-500">*</span>
                    </label>

                    <textarea wire:model="keperluan" rows="4" placeholder="Contoh: Untuk keperluan administrasi kependudukan..."
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700
                               placeholder:text-slate-400
                               focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20"></textarea>

                    @error('keperluan')
                        <p class="mt-1.5 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- Footer --}}
                <div class="border-t border-slate-100 bg-slate-50 px-6 py-5">

                    <div class="flex items-center justify-between gap-4">

                        <a href="{{ route('masyarakat.surat.create') }}" wire:navigate
                            class="inline-flex items-center gap-2 rounded-xl
                                   border border-slate-200 bg-white px-5 py-3
                                   text-sm font-semibold text-slate-600
                                   transition hover:border-slate-300
                                   hover:bg-slate-100 hover:text-slate-800
                                   focus:outline-none focus:ring-2
                                   focus:ring-slate-200 focus:ring-offset-2">

                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />

                            </svg>

                            Kembali

                        </a>

                        <button type="submit" wire:loading.attr="disabled" wire:target="ajukan"
                            class="inline-flex items-center gap-2 rounded-xl
                                   bg-blue-900 px-5 py-3
                                   text-sm font-semibold text-white
                                   transition hover:bg-blue-800
                                   active:bg-blue-950
                                   focus:outline-none focus:ring-2
                                   focus:ring-blue-600 focus:ring-offset-2
                                   disabled:cursor-not-allowed disabled:opacity-60">

                            <span wire:loading.remove wire:target="ajukan">
                                {{ $modeRevisi ? 'Kirim Perbaikan' : 'Ajukan Surat' }}
                            </span>

                            <span wire:loading wire:target="ajukan">
                                {{ $modeRevisi ? 'Mengirim Perbaikan...' : 'Mengajukan...' }}
                            </span>

                            <svg wire:loading.remove wire:target="ajukan" class="h-4 w-4" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />

                            </svg>

                            <svg wire:loading wire:target="ajukan" class="h-4 w-4 animate-spin" fill="none"
                                viewBox="0 0 24 24">

                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4" />

                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />

                            </svg>

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>
