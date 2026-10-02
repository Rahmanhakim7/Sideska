<?php

namespace App\Enums;

enum PengajuanSuratStatus: string
{
    case DIAJUKAN = 'diajukan';
    case REVISI = 'revisi';
    case DIPROSES = 'diproses';
    case DITOLAK = 'ditolak';
    case MENUNGGU_TANDA_TANGAN = 'menunggu_tanda_tangan';
    case SELESAI = 'selesai';
}
