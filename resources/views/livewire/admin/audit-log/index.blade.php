<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Audit Log
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Riwayat aktivitas admin terhadap akun pengguna SIDESKA.
            </p>
        </div>
        <div class="shrink-0 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-500 shadow-sm">
            Total:
            <span class="font-semibold text-slate-700">
                {{ $logs->total() }}
            </span>
            aktivitas
        </div>
    </div>

    <div class="relative overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="border-b border-blue-100 bg-blue-50/70">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wide text-blue-900">
                            Waktu
                        </th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wide text-blue-900">
                            Pelaku
                        </th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wide text-blue-900">
                            Aksi
                        </th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wide text-blue-900">
                            Deskripsi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($logs as $log)
                        <tr class="transition hover:bg-slate-50">
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                <div class="font-medium text-slate-700">
                                    {{ $log->created_at?->format('d M Y') ?? '-' }}
                                </div>
                                <div class="text-xs text-slate-500">
                                    {{ $log->created_at?->format('H:i') ?? '-' }}
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-full bg-blue-100 text-sm font-bold text-blue-700">
                                        {{ strtoupper(substr($log->user?->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div class="text-sm font-semibold text-slate-800">
                                        {{ $log->user?->name ?? 'Sistem' }}
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @php
                                    $aksiBadge = match ($log->action) {
                                        \App\Enums\AuditAction::USER_ACTIVATED => 'bg-emerald-100 text-emerald-700',
                                        \App\Enums\AuditAction::USER_DEACTIVATED => 'bg-red-100 text-red-700',
                                        \App\Enums\AuditAction::USER_PASSWORD_RESET => 'bg-amber-100 text-amber-700',
                                        \App\Enums\AuditAction::USER_UPDATED => 'bg-blue-100 text-blue-700',
                                        \App\Enums\AuditAction::USER_DELETED => 'bg-rose-100 text-rose-700',
                                    };
                                @endphp
                                <span
                                    class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ $aksiBadge }}">
                                    {{ $log->action->label() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $log->description }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="mx-auto max-w-sm">
                                    <div
                                        class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-slate-700">
                                        Belum ada aktivitas tercatat.
                                    </p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Aktivitas admin terhadap akun pengguna akan tampil di sini.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div wire:loading.flex wire:target="nextPage,previousPage,gotoPage"
            class="absolute inset-0 z-10 items-center justify-center bg-white">
            <div class="flex flex-col items-center justify-center">
                <svg class="h-8 w-8 animate-spin text-blue-700" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4">
                    </circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                    </path>
                </svg>
                <p class="mt-3 text-sm font-medium text-slate-600">
                    Memuat aktivitas...
                </p>
            </div>
        </div>
    </div>

    @if ($logs->hasPages())
        <div class="border-t border-slate-200 bg-white px-6 py-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-sm text-slate-500">
                    Menampilkan
                    <span class="font-semibold text-slate-700">
                        {{ $logs->firstItem() ?? 0 }}
                    </span>
                    sampai
                    <span class="font-semibold text-slate-700">
                        {{ $logs->lastItem() ?? 0 }}
                    </span>
                    dari
                    <span class="font-semibold text-slate-700">
                        {{ $logs->total() }}
                    </span>
                    aktivitas
                </div>
                <div class="flex items-center gap-1">
                    @if ($logs->onFirstPage())
                        <span
                            class="inline-flex h-9 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm font-medium text-slate-400">
                            Sebelumnya
                        </span>
                    @else
                        <button type="button" wire:click="previousPage" wire:loading.attr="disabled"
                            class="inline-flex h-9 items-center justify-center rounded-lg border border-slate-200 bg-white px-3 text-sm font-medium text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
                            Sebelumnya
                        </button>
                    @endif
                    <div class="hidden items-center gap-1 sm:flex">
                        @foreach ($logs->getUrlRange(1, $logs->lastPage()) as $page => $url)
                            @if ($page == $logs->currentPage())
                                <span
                                    class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg bg-blue-800 px-3 text-sm font-semibold text-white shadow-sm">
                                    {{ $page }}
                                </span>
                            @else
                                <button type="button" wire:click="gotoPage({{ $page }})"
                                    wire:loading.attr="disabled"
                                    class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg border border-slate-200 bg-white px-3 text-sm font-medium text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
                                    {{ $page }}
                                </button>
                            @endif
                        @endforeach
                    </div>
                    @if ($logs->hasMorePages())
                        <button type="button" wire:click="nextPage" wire:loading.attr="disabled"
                            class="inline-flex h-9 items-center justify-center rounded-lg border border-slate-200 bg-white px-3 text-sm font-medium text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
                            Berikutnya
                        </button>
                    @else
                        <span
                            class="inline-flex h-9 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm font-medium text-slate-400">
                            Berikutnya
                        </span>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
