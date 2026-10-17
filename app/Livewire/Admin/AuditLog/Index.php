<?php

namespace App\Livewire\Admin\AuditLog;

use App\Models\AuditLog;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public function render()
    {
        $logs = AuditLog::query()
            ->with('user')
            ->latest()
            ->paginate(15);

        return view('livewire.admin.audit-log.index', [
            'logs' => $logs,
        ]);
    }
}
