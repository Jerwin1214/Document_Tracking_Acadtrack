<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    /**
     * Display a list of activity logs
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $logs = ActivityLog::with('user')
            ->when($search, function($query, $search) {
                $query->where('action', 'like', "%{$search}%")
                      ->orWhere('model', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('pages.admin.activity-logs.index', compact('logs', 'search'));
    }
}
