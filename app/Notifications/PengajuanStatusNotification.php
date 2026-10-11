<?php

namespace App\Notifications;

use App\Enums\PengajuanSuratStatus;
use App\Models\PengajuanSurat;
use Illuminate\Notifications\Notification;

class PengajuanStatusNotification extends Notification
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(
        public PengajuanSurat $pengajuan,
        public string $pesan,
        public string $url,
        public ?PengajuanSuratStatus $status = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'pengajuan_id' => $this->pengajuan->id,
            'jenis_surat' => $this->pengajuan->jenis_surat,
            'status' => $this->status?->value,
            'pesan' => $this->pesan,
            'url' => $this->url,
        ];
    }
}
