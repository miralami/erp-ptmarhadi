<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $module = $request->query('module');
        $action = $request->query('action');

        $logs = ActivityLog::with('user')
            ->when($module, fn($q) => $q->where('module', $module))
            ->when($action, fn($q) => $q->where('action', $action))
            ->latest()
            ->paginate(30);

        $modules = ActivityLog::select('module')->distinct()->pluck('module');
        $actions = ActivityLog::select('action')->distinct()->pluck('action');

        return view('activity-logs.index', compact('logs', 'module', 'action', 'modules', 'actions'));
    }

    public function show(ActivityLog $activityLog): View
    {
        $activityLog->load('user');
        return view('activity-logs.show', compact('activityLog'));
    }
}
