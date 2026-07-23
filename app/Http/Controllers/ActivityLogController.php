<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')
            ->latest();

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {

                $q->where('description', 'like', "%{$request->keyword}%")
                  ->orWhere('action', 'like', "%{$request->keyword}%");

            });
        }

        $logs = $query->paginate(20)->withQueryString();

        $today = ActivityLog::whereDate('created_at', today())->count();

        return view('activity-logs.index', [
            'logs' => $logs,
            'today' => $today,
            'total' => ActivityLog::count(),
        ]);
    }
}