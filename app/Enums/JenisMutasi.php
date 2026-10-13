<?php

namespace App\Enums;

enum JenisMutasi: string
{
    case MASUK = 'masuk';
    case KELUAR = 'keluar';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(
            fn (self $jenis): string => $jenis->value,
            self::cases()
        );
    }

    public function label(): string
    {
        return match ($this) {
            self::MASUK => 'Masuk',
            self::KELUAR => 'Keluar',
        };
    }
}
