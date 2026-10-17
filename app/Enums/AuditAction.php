<?php

namespace App\Enums;

enum AuditAction: string
{
    case USER_ACTIVATED = 'user_activated';
    case USER_DEACTIVATED = 'user_deactivated';
    case USER_PASSWORD_RESET = 'user_password_reset';
    case USER_UPDATED = 'user_updated';
    case USER_DELETED = 'user_deleted';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(
            fn (self $action): string => $action->value,
            self::cases()
        );
    }

    public function label(): string
    {
        return match ($this) {
            self::USER_ACTIVATED => 'Aktivasi Akun',
            self::USER_DEACTIVATED => 'Penonaktifan Akun',
            self::USER_PASSWORD_RESET => 'Reset Password',
            self::USER_UPDATED => 'Perubahan Akun',
            self::USER_DELETED => 'Penghapusan Akun',
        };
    }
}
