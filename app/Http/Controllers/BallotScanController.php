<?php

namespace App\Http\Controllers;

use App\Models\Ballot;
use App\Models\ElectionSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BallotScanController extends Controller
{
    public function index()
    {
        $countedBallots = Ballot::where('is_counted', true)->count();
        $totalBallots = Ballot::count();

        return view('scan.index', compact(
            'countedBallots',
            'totalBallots'
        ));
    }

    public function store(Request $request)
    {
        $setting = ElectionSetting::first();

        if (!$setting || $setting->status !== 'berlangsung') {
            return redirect()
                ->route('scan.index')
                ->with('scan_status', 'error')
                ->with('scan_message', 'Penghitungan surat suara sedang tidak dibuka.');
        }

        $validated = $request->validate([
            'token' => ['required', 'string'],
        ], [
            'token.required' => 'QR belum terbaca.',
        ]);

        $result = DB::transaction(function () use ($validated) {
            $ballot = Ballot::query()
                ->with('candidate')
                ->where('token', trim($validated['token']))
                ->lockForUpdate()
                ->first();

            if (!$ballot) {
                return [
                    'status' => 'error',
                    'message' => 'QR tidak valid atau tidak terdaftar.',
                ];
            }

            if ($ballot->is_counted) {
                return [
                    'status' => 'duplicate',
                    'message' => 'QR ini sudah pernah dihitung.',
                ];
            }

            $ballot->update([
                'is_counted' => true,
                'counted_at' => now(),
            ]);

            return [
                'status' => 'success',
                'message' => 'Suara sah untuk kandidat nomor '
                    . $ballot->candidate->number . '.',
            ];
        });

        return redirect()
            ->route('scan.index')
            ->with('scan_status', $result['status'])
            ->with('scan_message', $result['message']);
    }
}