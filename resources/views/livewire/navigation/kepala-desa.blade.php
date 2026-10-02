<a href="{{ route('kepala.dashboard') }}" wire:navigate
    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
           {{ request()->routeIs('kepala.dashboard')
               ? 'bg-blue-50 text-blue-700'
               : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10" />
    </svg>
    Dashboard
</a>
<a href="{{ route('kepala.persetujuan') }}" wire:navigate
    class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium
           {{ request()->routeIs('kepala.persetujuan*')
               ? 'bg-blue-50 text-blue-700'
               : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800' }}">
    <div class="flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m5-3v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h4l2-2h4l2 2h2a2 2 0 012 2z" />
        </svg>
        Persetujuan
    </div>
</a>