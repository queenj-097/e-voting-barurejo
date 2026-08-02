<?php

namespace App\Http\Controllers;

use App\Models\Ballot;
use App\Models\ElectionSetting;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BallotScanController extends Controller
{
    /**
     * Menampilkan halaman scanner dan daftar
     * surat suara yang belum dihitung.
     */
    public function index()
    {
        $countedBallots = Ballot::query()
            ->where('is_counted', true)
            ->count();

        $totalBallots = Ballot::query()->count();

        $uncountedBallots = Ballot::query()
            ->where('is_counted', false)
            ->latest()
            ->limit(20)
            ->get();

        return view('scan.index', compact(
            'countedBallots',
            'totalBallots',
            'uncountedBallots'
        ));
    }

    /**
     * Memvalidasi token QR dan menghitung suara.
     */
    public function store(Request $request)
    {
        $setting = ElectionSetting::query()->first();

        if (
            !$setting
            || $setting->status !== 'berlangsung'
        ) {
            return redirect()
                ->route('scan.index')
                ->with('scan_status', 'error')
                ->with(
                    'scan_message',
                    'Penghitungan surat suara sedang tidak dibuka.'
                );
        }

        $validated = $request->validate([
            'token' => [
                'required',
                'string',
            ],
        ], [
            'token.required' => 'QR belum terbaca.',
        ]);

        $result = DB::transaction(function () use ($validated) {
            $ballot = Ballot::query()
                ->with([
                    'candidate.dusuns',
                ])
                ->where(
                    'token',
                    trim($validated['token'])
                )
                ->lockForUpdate()
                ->first();

            if (!$ballot) {
                return [
                    'status' => 'error',
                    'message' =>
                        'QR tidak valid atau tidak terdaftar.',
                    'scan_result' => null,
                ];
            }

            if ($ballot->is_counted) {
                return [
                    'status' => 'duplicate',
                    'message' =>
                        'QR ini sudah pernah dihitung.',
                    'scan_result' => [
                        'candidate_number' =>
                            $ballot->candidate?->number,
                        'candidate_name' =>
                            $ballot->candidate?->name,
                        'dusuns' =>
                            $ballot->candidate
                                ?->dusuns
                                ?->pluck('name')
                                ->implode(', '),
                        'counted_at' =>
                            $ballot->counted_at
                                ?->format('d-m-Y H:i:s'),
                    ],
                ];
            }

            $ballot->update([
                'is_counted' => true,
                'counted_at' => now(),
            ]);

            $ballot->refresh();

            $totalCountedBallots = Ballot::query()
                ->where('is_counted', true)
                ->count();

            $candidateVotes = Ballot::query()
                ->where('candidate_id', $ballot->candidate_id)
                ->where('is_counted', true)
                ->count();

            $dusunNames = $ballot->candidate
                ?->dusuns
                ?->pluck('name')
                ->implode(', ');

            return [
                'status' => 'success',
                'message' =>
                    'Surat suara berhasil divalidasi dan dihitung.',
                'scan_result' => [
                    'candidate_number' =>
                        $ballot->candidate?->number,
                    'candidate_name' =>
                        $ballot->candidate?->name,
                    'candidate_photo' =>
                        $ballot->candidate?->photo
                            ? asset(
                                'storage/' .
                                $ballot->candidate->photo
                            )
                            : null,
                    'dusuns' =>
                        $dusunNames ?: '-',
                    'candidate_votes' =>
                        $candidateVotes,
                    'total_counted_ballots' =>
                        $totalCountedBallots,
                    'counted_at' =>
                        $ballot->counted_at
                            ?->format('d-m-Y H:i:s'),
                ],
            ];
        });

        $redirect = redirect()
            ->route('scan.index')
            ->with('scan_status', $result['status'])
            ->with('scan_message', $result['message']);

        if (!empty($result['scan_result'])) {
            $redirect->with(
                'scan_result',
                $result['scan_result']
            );
        }

        return $redirect;
    }

    /**
     * Mencetak ulang QR surat suara yang belum dihitung.
     */
    public function reprint(Ballot $ballot)
    {
        $setting = ElectionSetting::query()->first();

        if (
            !$setting
            || $setting->status !== 'berlangsung'
        ) {
            return redirect()
                ->route('scan.index')
                ->with('scan_status', 'error')
                ->with(
                    'scan_message',
                    'Pemungutan suara sedang tidak berlangsung.'
                );
        }

        /*
         * Surat suara yang sudah dihitung tidak boleh
         * dicetak ulang untuk mencegah penyalahgunaan.
         */
        if ($ballot->is_counted) {
            return redirect()
                ->route('scan.index')
                ->with('scan_status', 'error')
                ->with(
                    'scan_message',
                    'QR yang sudah dihitung tidak dapat dicetak ulang.'
                );
        }

        $writer = new SvgWriter();

        $qrCode = new QrCode(
            data: $ballot->token
        );

        $result = $writer->write($qrCode);

        return view('voting.receipt', [
            'ballot' => $ballot,
            'qrDataUri' => $result->getDataUri(),
            'returnUrl' => route('scan.index'),
        ]);
    }
}