<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    /**
     * Menampilkan daftar aktivitas sistem.
     */
    public function index(Request $request): View
    {
        $query = ActivityLog::query()
            ->with('user')
            ->latest();

        if ($request->filled('action')) {
            $query->where(
                'action',
                $request->string('action')->toString()
            );
        }

        if ($request->filled('keyword')) {
            $keyword = trim(
                $request->string('keyword')->toString()
            );

            $query->where(function ($subQuery) use ($keyword) {
                $subQuery
                    ->where(
                        'description',
                        'like',
                        '%' . $keyword . '%'
                    )
                    ->orWhere(
                        'action',
                        'like',
                        '%' . $keyword . '%'
                    )
                    ->orWhereHas(
                        'user',
                        function ($userQuery) use ($keyword) {
                            $userQuery->where(
                                'name',
                                'like',
                                '%' . $keyword . '%'
                            );
                        }
                    );
            });
        }

        $logs = $query
            ->paginate(20)
            ->withQueryString();

        $today = ActivityLog::query()
            ->whereDate('created_at', today())
            ->count();

        $total = ActivityLog::query()->count();

        return view('activity-logs.index', compact(
            'logs',
            'today',
            'total'
        ));
    }

    /**
     * Menghapus satu aktivitas.
     */
    public function destroy(
        ActivityLog $activityLog
    ): RedirectResponse {
        $activityLog->delete();

        return back()->with(
            'success',
            'Aktivitas berhasil dihapus.'
        );
    }

    /**
     * Menghapus seluruh aktivitas.
     */
    public function destroyAll(): RedirectResponse
    {
        ActivityLog::query()->delete();

        return redirect()
            ->route('activity-logs.index')
            ->with(
                'success',
                'Seluruh riwayat aktivitas berhasil dihapus.'
            );
    }
}