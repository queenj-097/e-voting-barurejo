<?php

namespace App\Http\Controllers;

use App\Models\Booth;
use App\Models\ElectionSetting;
use App\Models\Voter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerificationController extends Controller
{
    public function index()
    {
        return view('verification.index');
    }

    public function verify(Request $request)
    {
        $setting = ElectionSetting::first();

        if (!$setting || $setting->status !== 'berlangsung') {
            return back()->with(
                'error',
                'Pemungutan suara belum dibuka atau sudah selesai.'
            );
        }

        $validated = $request->validate([
            'identity' => ['required', 'string'],
        ], [
            'identity.required' =>
                'Silakan scan QR atau masukkan kode pemilih.',
        ]);

        $identity = trim($validated['identity']);

        $voter = Voter::query()
            ->where(function ($query) use ($identity) {
                $query
                    ->where('voter_code', $identity)
                    ->orWhere('nik', $identity);
            })
            ->first();

        if (!$voter) {
            return back()
                ->withInput()
                ->with('error', 'Data pemilih tidak ditemukan.');
        }

        if ($voter->has_voted) {
            return back()->with(
                'error',
                'Pemilih ini sudah menggunakan hak pilih.'
            );
        }

        $assignment = DB::transaction(function () use ($voter) {
            $lockedVoter = Voter::query()
                ->lockForUpdate()
                ->findOrFail($voter->id);

            if ($lockedVoter->has_voted) {
                return [
                    'error' => 'Pemilih ini sudah menggunakan hak pilih.',
                ];
            }

            $alreadyAssigned = Booth::query()
                ->where('current_voter_id', $lockedVoter->id)
                ->lockForUpdate()
                ->first();

            if ($alreadyAssigned) {
                return [
                    'error' =>
                        'Pemilih ini sudah dikirim ke ' .
                        $alreadyAssigned->name .
                        '.',
                ];
            }

            $booths = Booth::query()
                ->lockForUpdate()
                ->orderBy('id')
                ->get();

            $booth = $booths->first(function (Booth $booth) {
                return $booth->isAvailable();
            });

            if (!$booth) {
                return [
                    'error' =>
                        'Bilik masih digunakan. Silakan tunggu sampai pemilih sebelumnya selesai.',
                ];
            }

            $booth->update([
                'status' => 'assigned',
                'current_voter_id' => $lockedVoter->id,
                'assigned_at' => now(),
                'voting_started_at' => null,
            ]);

            return [
                'voter_name' => $lockedVoter->name,
                'voter_code' => $lockedVoter->voter_code,
                'booth_name' => $booth->name,
            ];
        });

        if (isset($assignment['error'])) {
            return redirect()
                ->route('verification.index')
                ->with('error', $assignment['error']);
        }

        return redirect()
            ->route('verification.index')
            ->with(
                'success',
                'Pemilih berhasil dikirim ke bilik.'
            )
            ->with('assignment', $assignment);
    }

    public function result()
    {
        return redirect()->route('verification.index');
    }

    public function cancel()
    {
        session()->forget('verified_voter_id');

        return redirect()->route('verification.index');
    }

    public function assignToBooth()
    {
        return redirect()->route('verification.index');
    }
}