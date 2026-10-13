<?php

namespace App\Enums;

enum StatusKependudukan: string
{
    case TETAP = 'Tetap';
    case PENDATANG = 'Pendatang';
    case PINDAH = 'Pindah';
    case TIDAK_DIKETAHUI = 'Tidak Diketahui';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(
            fn (self $status): string => $status->value,
            self::cases()
        );
    }

    /**
     * Statuses that may be chosen when registering a new penduduk.
     *
     * `Pindah` is intentionally excluded: a new penduduk is never created
     * in an already-moved state. It remains a valid stored state that is
     * only produced by the future mutasi keluar flow.
     *
     * @return array<int, string>
     */
    public static function creatableValues(): array
    {
        return [
            self::TETAP->value,
            self::PENDATANG->value,
            self::TIDAK_DIKETAHUI->value,
        ];
    }

    public function label(): string
    {
        return $this->value;
    }
}
