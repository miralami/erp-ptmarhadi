<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    public function log(
        string $module,
        ?int $recordId = null,
        string $action = 'created',
        ?string $description = null,
        ?array $oldValue = null,
        ?array $newValue = null,
    ): ActivityLog {
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'module' => $module,
            'record_id' => $recordId,
            'action' => $action,
            'description' => $description,
            'old_value' => $oldValue,
            'new_value' => $newValue,
        ]);
    }
}
