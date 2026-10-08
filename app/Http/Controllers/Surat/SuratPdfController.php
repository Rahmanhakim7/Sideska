<?php

namespace App\Http\Controllers\Surat;

use App\Models\PengajuanSurat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratPdfController
{
    public function show(PengajuanSurat $pengajuan)
    {
        $this->authorizeAccess($pengajuan);

        abort_unless($pengajuan->file_pdf, 404);

        abort_unless(
            Storage::disk('b2')->exists($pengajuan->file_pdf),
            404
        );

        $pdf = Storage::disk('b2')->get($pengajuan->file_pdf);

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header(
                'Content-Disposition',
                'inline; filename="'.basename($pengajuan->file_pdf).'"'
            );
    }

    public function download(PengajuanSurat $pengajuan)
    {
        $this->authorizeAccess($pengajuan);

        abort_unless($pengajuan->file_pdf, 404);

        abort_unless(
            Storage::disk('b2')->exists($pengajuan->file_pdf),
            404
        );

        $pdf = Storage::disk('b2')->get($pengajuan->file_pdf);

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header(
                'Content-Disposition',
                'attachment; filename="'.basename($pengajuan->file_pdf).'"'
            );
    }

    private function authorizeAccess(PengajuanSurat $pengajuan): void
    {
        if (Auth::user()?->role !== 'masyarakat') {
            return;
        }

        abort_unless(
            $pengajuan->penduduk_id === Auth::user()?->penduduk?->id,
            403
        );
    }
}
