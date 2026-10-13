<div class="w-full">
    <div class="mb-6 flex flex-col gap-1">
        <a href="{{ route('perangkat-desa.penduduk.index') }}"
            class="w-fit text-sm font-medium text-blue-700 transition hover:text-blue-800">
            &larr; Kembali ke Data Penduduk
        </a>
        <h1 class="text-xl font-bold text-slate-800">
            Mutasi Keluar
        </h1>
        <p class="text-sm text-slate-500">
            Catat perpindahan penduduk keluar dari Desa Sekartejo.
        </p>
    </div>

    @error('penduduk')
        <div class="mb-6 flex gap-3 rounded-xl border border-red-200 bg-red-50 p-4">
            <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01M10.29 3.86l-7.82 14a2 2 0 001.74 3h15.58a2 2 0 001.74-3l-7.82-14a2 2 0 00-3.42 0z" />
            </svg>

            <p class="text-sm leading-5 text-red-700">{{ $message }}</p>
        </div>
    @enderror

    <div class="mb-6 flex gap-3 rounded-xl border border-amber-100 bg-amber-50 p-4">
        <svg class="mt-0.5 h-5 w-5 shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="9" stroke-width="1.5" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 10v6m0-9h.01" />
        </svg>

        <p class="text-xs leading-5 text-slate-600">
            Status penduduk menjadi
            <span class="font-semibold text-amber-700">Pindah</span>
            dan akun SIDESKA terkait otomatis dinonaktifkan. Data penduduk dan
            akun tidak dihapus.
        </p>
    </div>

    <form wire:submit.prevent="simpan" class="space-y-6">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-base font-bold text-slate-800">Penduduk</h2>
                <p class="text-xs text-slate-500">Penduduk yang akan dimutasi keluar</p>
            </div>

            <dl class="grid grid-cols-1 gap-x-6 gap-y-4 p-6 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Nama</dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-800">{{ $penduduk->nama }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">NIK</dt>
                    <dd class="mt-1 text-sm text-slate-700">{{ $penduduk->nik }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Alamat Asal</dt>
                    <dd class="mt-1 text-sm text-slate-700">{{ $alamatAsal }}</dd>
                </div>
            </dl>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-base font-bold text-slate-800">Detail Mutasi</h2>
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

                <div>
                    <label for="alasan" class="mb-2 block text-sm font-semibold text-slate-700">
                        Alasan
                    </label>
                    <input id="alasan" type="text" wire:model="alasan"
                        placeholder="Contoh: Pekerjaan, Pendidikan"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none placeholder:text-slate-400 transition
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    @error('alasan')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="alamat_tujuan" class="mb-2 block text-sm font-semibold text-slate-700">
                        Alamat Tujuan <span class="text-red-500">*</span>
                    </label>
                    <textarea id="alamat_tujuan" wire:model="alamat_tujuan" rows="3"
                        placeholder="Alamat lengkap tujuan pindah"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none placeholder:text-slate-400 transition
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100"></textarea>
                    @error('alamat_tujuan')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="catatan" class="mb-2 block text-sm font-semibold text-slate-700">
                        Catatan
                    </label>
                    <textarea id="catatan" wire:model="catatan" rows="3"
                        placeholder="Catatan tambahan (opsional)"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm
                               text-slate-700 outline-none placeholder:text-slate-400 transition
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100"></textarea>
                    @error('catatan')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('perangkat-desa.penduduk.index') }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                Batal
            </a>

            <button type="submit" wire:loading.attr="disabled" wire:target="simpan"
                class="inline-flex items-center justify-center rounded-xl bg-amber-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60">
                <span wire:loading.remove wire:target="simpan">
                    Simpan Mutasi Keluar
                </span>
                <span wire:loading wire:target="simpan">
                    Menyimpan...
                </span>
            </button>
        </div>
    </form>
</div>
