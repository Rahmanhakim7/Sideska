<div class="min-h-screen bg-slate-50">

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">

        <div class="mb-6">

            <h1 class="text-2xl font-bold text-slate-800">
                Surat Keterangan Tidak Mampu
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Silakan periksa data diri dan lengkapi keperluan pengajuan surat.
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

                                    <p class="mt-0.5 text-sm text-amber-700">
                                        Silakan periksa dan perbaiki data yang diperlukan sebelum mengirim kembali
                                        pengajuan.
                                    </p>

                                </div>

                            </div>

                        </div>
                    @endif

                </div>

                <div class="grid gap-5 px-6 py-6 sm:grid-cols-2">

                    {{-- NIK --}}
                    <div>

                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            NIK
                        </label>

                        <input type="text" wire:model="nik" @readonly(!$modeRevisi)
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5
                                   text-sm text-slate-600
                                   {{ !$modeRevisi ? 'bg-slate-50' : 'bg-white' }}
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">

                        @error('nik')
                            <p class="mt-1.5 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Nomor KK --}}
                    <div>

                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Nomor KK
                        </label>

                        <input type="text" wire:model="no_kk" @readonly(!$modeRevisi)
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5
                                   text-sm text-slate-600
                                   {{ !$modeRevisi ? 'bg-slate-50' : 'bg-white' }}
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">

                        @error('no_kk')
                            <p class="mt-1.5 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Nama Lengkap --}}
                    <div>

                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Nama Lengkap
                        </label>

                        <input type="text" wire:model="nama" @readonly(!$modeRevisi)
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5
                                   text-sm text-slate-600
                                   {{ !$modeRevisi ? 'bg-slate-50' : 'bg-white' }}
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">

                        @error('nama')
                            <p class="mt-1.5 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Tempat Lahir --}}
                    <div>

                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Tempat Lahir
                        </label>

                        <input type="text" wire:model="tempat_lahir" @readonly(!$modeRevisi)
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5
                                   text-sm text-slate-600
                                   {{ !$modeRevisi ? 'bg-slate-50' : 'bg-white' }}
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">

                        @error('tempat_lahir')
                            <p class="mt-1.5 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Tanggal Lahir --}}
                    <div>

                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Tanggal Lahir
                        </label>

                        <input type="{{ $modeRevisi ? 'date' : 'text' }}" wire:model="tanggal_lahir" @readonly(!$modeRevisi)
                            @if (!$modeRevisi) value="{{ $penduduk->tanggal_lahir?->format('d-m-Y') }}" @endif
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5
                                   text-sm text-slate-600
                                   {{ !$modeRevisi ? 'bg-slate-50' : 'bg-white' }}
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">

                        @error('tanggal_lahir')
                            <p class="mt-1.5 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Jenis Kelamin --}}
                    <div>

                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Jenis Kelamin
                        </label>

                        <input type="text" wire:model="jenis_kelamin" @readonly(!$modeRevisi)
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5
                                   text-sm text-slate-600
                                   {{ !$modeRevisi ? 'bg-slate-50' : 'bg-white' }}
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">

                        @error('jenis_kelamin')
                            <p class="mt-1.5 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Alamat --}}
                    <div class="sm:col-span-2">

                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Alamat
                        </label>

                        @if ($modeRevisi)
                            <div class="grid gap-4 sm:grid-cols-2">

                                <div class="sm:col-span-2">

                                    <textarea wire:model="alamat" rows="2" placeholder="Alamat lengkap"
                                        class="w-full resize-none rounded-xl border border-slate-200 bg-white
                                               px-4 py-2.5 text-sm text-slate-600
                                               placeholder:text-slate-400
                                               focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20"></textarea>

                                    @error('alamat')
                                        <p class="mt-1.5 text-sm text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>

                                <div>

                                    <label class="mb-1.5 block text-xs font-medium text-slate-600">
                                        RT
                                    </label>

                                    <input type="text" wire:model="rt"
                                        class="w-full rounded-xl border border-slate-200 bg-white
                                               px-4 py-2.5 text-sm text-slate-600
                                               focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">

                                    @error('rt')
                                        <p class="mt-1.5 text-sm text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>

                                <div>

                                    <label class="mb-1.5 block text-xs font-medium text-slate-600">
                                        RW
                                    </label>

                                    <input type="text" wire:model="rw"
                                        class="w-full rounded-xl border border-slate-200 bg-white
                                               px-4 py-2.5 text-sm text-slate-600
                                               focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">

                                    @error('rw')
                                        <p class="mt-1.5 text-sm text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>

                                <div class="sm:col-span-2">

                                    <label class="mb-1.5 block text-xs font-medium text-slate-600">
                                        Dusun
                                    </label>

                                    <input type="text" wire:model="dusun"
                                        class="w-full rounded-xl border border-slate-200 bg-white
                                               px-4 py-2.5 text-sm text-slate-600
                                               focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">

                                    @error('dusun')
                                        <p class="mt-1.5 text-sm text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>

                            </div>
                        @else
                            <textarea readonly rows="2"
                                class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50
                                       px-4 py-2.5 text-sm text-slate-600
                                       focus:border-slate-200 focus:ring-0">{{ $penduduk->alamat }}, RT {{ $penduduk->rt }}/RW {{ $penduduk->rw }}, {{ $penduduk->dusun }}</textarea>
                        @endif

                    </div>

                </div>

                {{-- Data Orang Tua --}}
                <div class="border-t border-slate-100 px-6 py-5">

                    <h2 class="text-base font-semibold text-slate-800">
                        Data Orang Tua
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Lengkapi pekerjaan dan penghasilan orang tua untuk kebutuhan Surat
                        Keterangan Tidak Mampu.
                    </p>

                </div>

                <div class="grid gap-6 px-6 pb-6 sm:grid-cols-2">

                    {{-- AYAH --}}
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">

                        <div class="mb-5">
                            <h3 class="text-sm font-semibold text-slate-800">
                                Data Ayah
                            </h3>

                            <p class="mt-1 text-xs text-slate-500">
                                Data nama diambil dari data kependudukan.
                            </p>
                        </div>

                        {{-- Nama Ayah --}}
                        <div class="mb-4">

                            <label class="mb-1.5 block text-sm font-medium text-slate-700">
                                Nama Ayah
                            </label>

                            <input type="text" value="{{ $penduduk->nama_ayah ?? '-' }}" readonly
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5
                       text-sm text-slate-600 focus:border-slate-200 focus:ring-0">

                        </div>

                        {{-- Pekerjaan Ayah --}}
                        <div class="mb-4">

                            <label class="mb-1.5 block text-sm font-medium text-slate-700">
                                Pekerjaan Ayah
                                <span class="text-red-500">*</span>
                            </label>

                            <input type="text" wire:model="pekerjaanAyah" placeholder="Contoh: Petani"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5
                       text-sm text-slate-600
                       placeholder:text-slate-400
                       focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">

                            @error('pekerjaanAyah')
                                <p class="mt-1.5 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- Penghasilan Ayah --}}
                        <div>

                            <label class="mb-1.5 block text-sm font-medium text-slate-700">
                                Penghasilan Ayah
                                <span class="text-red-500">*</span>
                            </label>

                            <input type="text" wire:model="penghasilanAyah" placeholder="Contoh: 1500000"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5
                       text-sm text-slate-600
                       placeholder:text-slate-400
                       focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">

                            @error('penghasilanAyah')
                                <p class="mt-1.5 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>


                    {{-- IBU --}}
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">

                        <div class="mb-5">
                            <h3 class="text-sm font-semibold text-slate-800">
                                Data Ibu
                            </h3>

                            <p class="mt-1 text-xs text-slate-500">
                                Data nama diambil dari data kependudukan.
                            </p>
                        </div>

                        {{-- Nama Ibu --}}
                        <div class="mb-4">

                            <label class="mb-1.5 block text-sm font-medium text-slate-700">
                                Nama Ibu
                            </label>

                            <input type="text" value="{{ $penduduk->nama_ibu ?? '-' }}" readonly
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5
                       text-sm text-slate-600 focus:border-slate-200 focus:ring-0">

                        </div>

                        {{-- Pekerjaan Ibu --}}
                        <div class="mb-4">

                            <label class="mb-1.5 block text-sm font-medium text-slate-700">
                                Pekerjaan Ibu
                                <span class="text-red-500">*</span>
                            </label>

                            <input type="text" wire:model="pekerjaanIbu" placeholder="Contoh: Ibu Rumah Tangga"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5
                       text-sm text-slate-600
                       placeholder:text-slate-400
                       focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">

                            @error('pekerjaanIbu')
                                <p class="mt-1.5 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- Penghasilan Ibu --}}
                        <div>

                            <label class="mb-1.5 block text-sm font-medium text-slate-700">
                                Penghasilan Ibu
                                <span class="text-red-500">*</span>
                            </label>

                            <input type="text" wire:model="penghasilanIbu" placeholder="Contoh: 500000"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5
                       text-sm text-slate-600
                       placeholder:text-slate-400
                       focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">

                            @error('penghasilanIbu')
                                <p class="mt-1.5 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>

                </div>

                {{-- Keperluan --}}
                <div class="border-t border-slate-100 px-6 py-5">

                    <h2 class="text-base font-semibold text-slate-800">
                        Keperluan Surat
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Jelaskan keperluan Anda mengajukan Surat Keterangan Tidak Mampu.
                    </p>

                </div>

                <div class="px-6 pb-6">

                    <label class="mb-1.5 block text-sm font-medium text-slate-700">
                        Keperluan <span class="text-red-500">*</span>
                    </label>

                    <textarea wire:model="keperluan" rows="4" placeholder="Contoh: Untuk pengajuan bantuan pendidikan..."
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
                                   focus:ring-slate-300 focus:ring-offset-2">

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
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4" />

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
