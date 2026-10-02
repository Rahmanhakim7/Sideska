<div class="space-y-6">
    <div class="justify-between flex items-center gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800">
                Pembuatan Surat
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Buat surat berdasarkan pengajuan masyarakat.
            </p>
        </div>
        <a href="{{ route('perangkat-desa.pelayanan-surat.detail', $pengajuan->id) }}" wire:navigate
            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>

            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-base font-semibold text-slate-800">
                    Informasi Pengajuan
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Data pengajuan yang akan dibuatkan surat.
                </p>
            </div>

            <div class="space-y-6 px-6 py-6">

                {{-- Jenis Surat --}}
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Jenis Surat
                    </p>

                    <p class="mt-2 text-sm font-semibold text-slate-800">
                        {{ ucwords(str_replace('_', ' ', $pengajuan->jenis_surat)) }}
                    </p>
                </div>


                {{-- Tanggal Pengajuan --}}
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Tanggal Pengajuan
                    </p>

                    <p class="mt-2 text-sm font-semibold text-slate-800">
                        {{ $pengajuan->created_at?->format('d F Y') }}
                    </p>
                </div>


                {{-- Keperluan --}}
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Keperluan
                    </p>

                    <div class="mt-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5">

                        <p class="break-words text-sm leading-6 text-slate-700">
                            {{ $pengajuan->keperluan ?: '-' }}
                        </p>

                    </div>
                </div>

            </div>
        </div>
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-base font-semibold text-slate-800">
                    Data Pemohon
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Data kependudukan pemohon yang digunakan dalam surat.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-6 px-6 py-6 sm:grid-cols-2">

                {{-- Nama Lengkap --}}
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Nama Lengkap
                    </p>

                    <p class="mt-2 text-sm font-semibold text-slate-800">
                        {{ $pengajuan->penduduk->nama }}
                    </p>
                </div>


                {{-- NIK --}}
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        NIK
                    </p>

                    <p class="mt-2 text-sm font-semibold text-slate-800">
                        {{ $pengajuan->penduduk->nik }}
                    </p>
                </div>


                {{-- Nomor KK --}}
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Nomor KK
                    </p>

                    <p class="mt-2 text-sm font-semibold text-slate-800">
                        {{ $pengajuan->penduduk->no_kk }}
                    </p>
                </div>


                {{-- Nomor HP --}}
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Nomor HP
                    </p>

                    <p class="mt-2 text-sm font-semibold text-slate-800">
                        {{ $pengajuan->penduduk->no_hp ?: '-' }}
                    </p>
                </div>


                {{-- Alamat --}}
                <div class="sm:col-span-2">
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Alamat
                    </p>

                    <p class="mt-2 text-sm leading-6 text-slate-700">
                        {{ $pengajuan->penduduk->alamat }},
                        RT {{ $pengajuan->penduduk->rt }}/RW {{ $pengajuan->penduduk->rw }},
                        {{ $pengajuan->penduduk->dusun }}
                    </p>
                </div>

            </div>
        </div>
    </div>


    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-5">
            <h2 class="text-base font-semibold text-slate-800">
                Detail Surat
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Lengkapi informasi yang diperlukan untuk pembuatan surat.
            </p>
        </div>
        <div class="px-6 py-6">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                @forelse ($fields as $field)
                    <div>
                        <label for="{{ $field['name'] }}" class="block text-sm font-medium text-slate-700">
                            {{ $field['label'] }}
                            @if ($field['required'])
                                <span class="text-red-500">*</span>
                            @endif
                        </label>
                        <div class="mt-2">
                            <input id="{{ $field['name'] }}" type="{{ $field['type'] }}"
                                wire:model="data.{{ $field['name'] }}"
                                class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                        </div>
                        @error('data.' . $field['name'])
                            <p class="mt-1.5 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                @empty
                    <div
                        class="sm:col-span-2 rounded-xl border border-dashed border-slate-300 bg-slate-50 px-5 py-8 text-center">
                        <p class="text-sm font-medium text-slate-700">
                            Form surat belum tersedia
                        </p>
                        <p class="mt-1 text-sm text-slate-500">
                            Format surat untuk jenis pengajuan ini belum dikonfigurasi.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
        <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50 px-6 py-4">
            <a href="{{ route('perangkat-desa.pelayanan-surat.detail', $pengajuan->id) }}" wire:navigate
                class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-600 transition hover:border-slate-300 hover:bg-slate-50">
                Batal
            </a>
            <button type="button" wire:click="simpan" wire:loading.attr="disabled"
                class="inline-flex items-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-60">
                <span wire:loading.remove wire:target="simpan">
                    Buat Surat
                </span>
                <span wire:loading wire:target="simpan">
                    Menyimpan...
                </span>
            </button>
        </div>
    </div>
</div>
