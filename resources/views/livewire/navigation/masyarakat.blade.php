<a href="{{ route('dashboard') }}" wire:navigate
    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
           {{ request()->routeIs('dashboard')
               ? 'bg-blue-50 text-blue-700'
               : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10" />
    </svg>
    Dashboard
</a>
<a href="{{ route('masyarakat.surat.create') }}" wire:navigate
    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
           {{ request()->routeIs('masyarakat.surat.create')
               ? 'bg-blue-50 text-blue-700'
               : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z" />
    </svg>
    Ajukan Surat
</a>
<a href="{{ route('masyarakat.surat.riwayat') }}" wire:navigate
    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('masyarakat.surat.riwayat') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l2.5 1.5" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    Riwayat Pengajuan
</a>
