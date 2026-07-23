<?php

namespace App\Http\Controllers;

use App\Models\Ballot;
use App\Models\Booth;
use App\Models\Candidate;
use App\Models\Voter;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\ElectionSetting;

class VotingController extends Controller
{
    public function index()
    {
        $setting = ElectionSetting::first();

        if (!$setting || $setting->status !== 'berlangsung') {
            return redirect()
                ->route('verification.index')
                ->with('error', 'Pemungutan suara sedang tidak berlangsung.');
        }

        $voterId = session('verified_voter_id');

        if (!$voterId) {
            return redirect()
                ->route('verification.index')
                ->with('error', 'Silakan verifikasi pemilih terlebih dahulu.');
        }

        $voter = Voter::findOrFail($voterId);

        if ($voter->has_voted) {
            session()->forget([
                'verified_voter_id',
                'active_booth_id',
            ]);

            return redirect()
                ->route('verification.index')
                ->with('error', 'Pemilih ini sudah menggunakan hak pilih.');
        }

        $candidates = Candidate::query()
            ->orderBy('number')
            ->get();

        return view('voting.index', compact('voter', 'candidates'));
    }

    public function store(Request $request)
    {
        $setting = ElectionSetting::first();

        if (!$setting || $setting->status !== 'berlangsung') {
            return redirect()
                ->route('verification.index')
                ->with('error', 'Pemungutan suara sudah ditutup.');
        }

        $validated = $request->validate([
            'candidate_id' => [
                'required',
                'exists:candidates,id',
            ],
        ], [
            'candidate_id.required' => 'Silakan pilih salah satu kandidat.',
            'candidate_id.exists' => 'Kandidat yang dipilih tidak tersedia.',
        ]);

        $voterId = session('verified_voter_id');
        $boothId = session('active_booth_id');

        if (!$voterId) {
            return redirect()
                ->route('verification.index')
                ->with('error', 'Sesi verifikasi sudah tidak tersedia.');
        }

        $ballot = DB::transaction(function () use (
            $validated,
            $voterId,
            $boothId
        ) {
            $voter = Voter::query()
                ->lockForUpdate()
                ->findOrFail($voterId);

            if ($voter->has_voted) {
                abort(403, 'Pemilih ini sudah menggunakan hak pilih.');
            }

            $booth = null;

            if ($boothId) {
                $booth = Booth::query()
                    ->lockForUpdate()
                    ->findOrFail($boothId);

                if (
                    (int) $booth->current_voter_id
                    !== (int) $voter->id
                ) {
                    abort(
                        403,
                        'Pemilih tidak sesuai dengan bilik yang digunakan.'
                    );
                }

                if ($booth->status !== 'voting') {
                    abort(
                        403,
                        'Status bilik tidak valid untuk melakukan pemilihan.'
                    );
                }
            }

            $ballot = Ballot::create([
                'candidate_id' => $validated['candidate_id'],
                'token' => (string) Str::uuid(),
                'is_counted' => false,
            ]);

            $voter->update([
                'has_voted' => true,
                'voted_at' => now(),
            ]);

            if ($booth) {
                $booth->update([
                    'status' => 'idle',
                    'current_voter_id' => null,
                    'assigned_at' => null,
                    'voting_started_at' => null,
                ]);
            }

            return $ballot;
        });

        /*
         * Simpan ID bilik sebelum session dihapus.
         * ID ini dikirim ke halaman receipt sebagai query parameter.
         */
        $returnBoothId = $boothId;

        session()->forget([
            'verified_voter_id',
            'active_booth_id',
        ]);

        return redirect()->route('voting.receipt', [
            'ballot' => $ballot,
            'booth' => $returnBoothId,
        ]);
    }

    public function receipt(Request $request, Ballot $ballot)
    {
        $writer = new SvgWriter();

        $qrCode = new QrCode(
            data: $ballot->token
        );

        $result = $writer->write($qrCode);

        $boothId = $request->integer('booth');

        $returnUrl = $boothId
            ? route('booths.show', $boothId)
            : route('verification.index');

        return view('voting.receipt', [
            'ballot' => $ballot,
            'qrDataUri' => $result->getDataUri(),
            'returnUrl' => $returnUrl,
        ]);
    }
}