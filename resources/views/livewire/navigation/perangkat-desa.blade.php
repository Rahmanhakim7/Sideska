<a href="{{ route('perangkat-desa.dashboard') }}" wire:navigate
    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
        {{ request()->routeIs('perangkat-desa.dashboard')
            ? 'bg-blue-50 text-blue-800'
            : 'text-slate-600 hover:bg-blue-50 hover:text-blue-800' }}">

    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10" />
    </svg>

    Dashboard
</a>

<a href="{{ route('perangkat-desa.pelayanan-surat') }}" wire:navigate
    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
        {{ request()->routeIs('perangkat-desa.pelayanan-surat')
            ? 'bg-blue-50 text-blue-800'
            : 'text-slate-600 hover:bg-blue-50 hover:text-blue-800' }}">
    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z" />
    </svg>
    Pelayanan Surat
</a>

<a href="{{ route('perangkat-desa.penduduk.index') }}" wire:navigate
    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
        {{ request()->routeIs('perangkat-desa.penduduk.*')
            ? 'bg-blue-50 text-blue-800'
            : 'text-slate-600 hover:bg-blue-50 hover:text-blue-800' }}">

    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-8a4 4 0 11-8 0 4 4 0 018 0zm6 2a3 3 0 10-6 0" />
    </svg>

    Data Penduduk
</a>


