<?php

namespace App\Http\Controllers;

use App\Models\Voter;
use Illuminate\Http\Request;
use App\Models\Booth;
use Illuminate\Support\Facades\DB;
use App\Models\ElectionSetting;

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
            'identity.required' => 'Masukkan NIK atau nomor DPT.',
        ]);

        $voter = Voter::query()
            ->where('nik', $validated['identity'])
            ->orWhere('dpt_number', $validated['identity'])
            ->first();

        if (!$voter) {
            return back()
                ->withInput()
                ->with('error', 'Data pemilih tidak ditemukan dalam DPT.');
        }

        if ($voter->has_voted) {
            return back()
                ->with('error', 'Pemilih ini sudah menggunakan hak pilih.');
        }

        session([
            'verified_voter_id' => $voter->id,
        ]);

        return redirect()
            ->route('verification.result')
            ->with('success', 'Data pemilih berhasil diverifikasi.');
    }

    public function result()
    {
        $voterId = session('verified_voter_id');

        if (!$voterId) {
            return redirect()
                ->route('verification.index')
                ->with('error', 'Silakan verifikasi pemilih terlebih dahulu.');
        }

        $voter = Voter::findOrFail($voterId);

        $booths = Booth::query()
            ->with('currentVoter')
            ->orderBy('id')
            ->get();

        return view('verification.result', compact('voter', 'booths'));
    }

    public function cancel()
    {
        session()->forget('verified_voter_id');

        return redirect()->route('verification.index');
    }

    public function assignToBooth(Request $request)
    {
        $setting = ElectionSetting::first();

        if (!$setting || $setting->status !== 'berlangsung') {
            return redirect()
                ->route('verification.index')
                ->with('error', 'Pemungutan suara sedang tidak berlangsung.');
        }

        $validated = $request->validate([
            'booth_id' => ['required', 'exists:booths,id'],
        ], [
            'booth_id.required' => 'Pilih bilik terlebih dahulu.',
            'booth_id.exists' => 'Bilik yang dipilih tidak ditemukan.',
        ]);

        $voterId = session('verified_voter_id');

        if (!$voterId) {
            return redirect()
                ->route('verification.index')
                ->with('error', 'Sesi verifikasi sudah tidak tersedia.');
        }

        $assignment = DB::transaction(function () use ($validated, $voterId) {
            $voter = Voter::query()
                ->lockForUpdate()
                ->findOrFail($voterId);

            if ($voter->has_voted) {
                abort(422, 'Pemilih sudah menggunakan hak pilih.');
            }

            $alreadyAssigned = Booth::query()
                ->where('current_voter_id', $voter->id)
                ->exists();

            if ($alreadyAssigned) {
                abort(422, 'Pemilih ini sudah dikirim ke bilik.');
            }

            $booth = Booth::query()
                ->lockForUpdate()
                ->findOrFail($validated['booth_id']);

            if (!$booth->isAvailable()) {
                abort(422, 'Bilik yang dipilih sedang tidak tersedia.');
            }

            $booth->update([
                'status' => 'assigned',
                'current_voter_id' => $voter->id,
                'assigned_at' => now(),
                'voting_started_at' => null,
            ]);

            return [
                'voter_name' => $voter->name,
                'dpt_number' => $voter->dpt_number,
                'booth_name' => $booth->name,
            ];
        });

        session()->forget('verified_voter_id');

        return redirect()
            ->route('verification.index')
            ->with('success', 'Pemilih berhasil dikirim ke bilik.')
            ->with('assignment', $assignment);
    }
}