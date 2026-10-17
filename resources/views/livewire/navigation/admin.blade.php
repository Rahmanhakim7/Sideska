<a href="{{ route('admin.dashboard') }}" wire:navigate
    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
           {{ request()->routeIs('admin.dashboard')
               ? 'bg-blue-50 text-blue-700'
               : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10" />
    </svg>

    Dashboard
</a>

<a href="{{ route('admin.pengguna') }}" wire:navigate
    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
           {{ request()->routeIs('admin.pengguna*')
               ? 'bg-blue-50 text-blue-700'
               : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 17v-2a4 4 0 014-4h4a4 4 0 014 4v2" />
    </svg>

    Pengguna
</a>

<a href="{{ route('admin.audit-log') }}" wire:navigate
    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
           {{ request()->routeIs('admin.audit-log')
               ? 'bg-blue-50 text-blue-700'
               : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>

    Audit Log
</a>
