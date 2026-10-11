@if ($histori->isEmpty())
    <p class="text-sm text-slate-500">
        Belum ada riwayat perubahan status.
    </p>
@else
    <ol class="relative ml-3 border-l-2 border-slate-200 pl-6">
        @foreach ($histori->reverse()->values() as $riwayat)

            @php
                $status = $riwayat->status_sesudah?->value;
                $badge = match ($status) {
                    'diajukan' => ['bg-blue-50', 'text-blue-700', 'bg-blue-500'],
                    'revisi' => ['bg-amber-50', 'text-amber-700', 'bg-amber-500'],
                    'diproses' => ['bg-indigo-50', 'text-indigo-700', 'bg-indigo-500'],
                    'ditolak' => ['bg-red-50', 'text-red-700', 'bg-red-500'],
                    'menunggu_tanda_tangan' => ['bg-violet-50', 'text-violet-700', 'bg-violet-500'],
                    'selesai' => ['bg-green-50', 'text-green-700', 'bg-green-500'],
                    default => ['bg-slate-100', 'text-slate-600', 'bg-slate-400'],
                };
            @endphp

            <li class="relative pb-8 last:pb-0">
                <span
                    class="absolute -left-[31px] top-0 flex h-4 w-4 items-center justify-center bg-white">
                    <span class="h-3 w-3 rounded-full {{ $badge[2] }}"></span>
                </span>

                <div class="flex flex-wrap items-center gap-2">
                    <span
                        class="inline-flex items-center rounded-full {{ $badge[0] }} px-2.5 py-0.5 text-xs font-semibold {{ $badge[1] }}">
                        {{ $riwayat->status_sesudah ? ucwords(str_replace('_', ' ', $riwayat->status_sesudah->value)) : '-' }}
                    </span>

                    @if ($riwayat->status_sebelum)
                        <span class="text-xs text-slate-400">
                            dari {{ ucwords(str_replace('_', ' ', $riwayat->status_sebelum->value)) }}
                        </span>
                    @else
                        <span class="text-xs text-slate-400">
                            pengajuan dibuat
                        </span>
                    @endif
                </div>

                <p class="mt-1.5 text-xs text-slate-500">
                    {{ $riwayat->user?->name ?? 'Sistem' }}
                    ·
                    {{ $riwayat->created_at->format('d M Y, H:i') }}
                </p>

                @if ($riwayat->catatan)
                    <p
                        class="mt-2 rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm leading-6 text-slate-600">
                        {{ $riwayat->catatan }}
                    </p>
                @endif
            </li>

        @endforeach
    </ol>
@endif