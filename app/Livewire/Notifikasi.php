<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Notifikasi extends Component
{
    use WithPagination;

    public function tandaiDibaca(string $notificationId): void
    {
        $notifikasi = auth()->user()
            ->notifications()
            ->findOrFail($notificationId);

        $notifikasi->markAsRead();
    }

    public function tandaiSemuaDibaca(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function hapus(string $notificationId): void
    {
        auth()->user()
            ->notifications()
            ->findOrFail($notificationId)
            ->delete();
    }

    public function render()
    {
        $notifikasi = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(10);

        return view('livewire.notifikasi', [
            'notifikasi' => $notifikasi,
        ]);
    }
}
