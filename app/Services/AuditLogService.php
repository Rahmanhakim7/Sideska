<?php

namespace App\Services;

use App\Enums\AuditAction;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AuditLogService
{
    /**
     * Catat satu aktivitas admin ke tabel audit_logs.
     *
     * Hanya menerima deskripsi teks yang sudah aman (tanpa password,
     * token, atau kredensial lain) karena nilai ini disimpan apa adanya.
     */
    public function catat(
        User $pelaku,
        AuditAction $action,
        string $description,
        ?Model $target = null
    ): AuditLog {
        return AuditLog::create([
            'user_id' => $pelaku->id,
            'action' => $action,
            'target_type' => $target?->getMorphClass(),
            'target_id' => $target?->getKey(),
            'description' => $description,
        ]);
    }
}
