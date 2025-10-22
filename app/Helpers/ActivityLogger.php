<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Log an activity
     *
     * @param string $action    Create, Update, Delete, etc.
     * @param string $model     Model or module affected
     * @param string|null $description Optional description
     */
    public static function log(string $action, string $model, ?string $description = null)
    {
        ActivityLog::create([
            'action' => $action,
            'model' => $model,
            'description' => $description,
            'user_id' => Auth::id(),
        ]);
    }
}
