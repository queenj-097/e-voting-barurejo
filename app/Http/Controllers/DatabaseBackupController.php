<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DatabaseBackupController extends Controller
{
    public function download(): BinaryFileResponse|RedirectResponse
    {
        $databasePath = database_path('database.sqlite');

        if (!file_exists($databasePath)) {
            return redirect()
                ->route('settings.edit')
                ->withErrors([
                    'backup' => 'File database SQLite tidak ditemukan.',
                ]);
        }

        $fileName = 'backup-e-voting-'
            . now()->format('Y-m-d_H-i-s')
            . '.sqlite';

        return response()->download(
            $databasePath,
            $fileName,
            [
                'Content-Type' => 'application/vnd.sqlite3',
                'Cache-Control' => 'no-store, no-cache, must-revalidate',
            ]
        );
    }
}