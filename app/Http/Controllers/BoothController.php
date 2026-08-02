<?php

namespace App\Http\Controllers;

use App\Models\Booth;
use App\Models\ElectionSetting;

class BoothController extends Controller
{
    public function show(Booth $booth)
    {
        $setting = ElectionSetting::first();

        if (!$setting || $setting->status !== 'berlangsung') {
            return view('booths.show', compact('booth'))
                ->with(
                    'error',
                    'Pemungutan suara belum dibuka atau sudah selesai.'
                );
        }

        $booth->load([
            'currentVoter.dusun',
        ]);

        if (
            $booth->status === 'assigned'
            && $booth->current_voter_id
        ) {
            $booth->update([
                'status' => 'voting',
                'voting_started_at' => now(),
            ]);

            session([
                'active_booth_id' => $booth->id,
                'verified_voter_id' => $booth->current_voter_id,
            ]);

            return redirect()->route('voting.index');
        }

        if (
            $booth->status === 'voting'
            && $booth->current_voter_id
        ) {
            session([
                'active_booth_id' => $booth->id,
                'verified_voter_id' => $booth->current_voter_id,
            ]);

            return redirect()->route('voting.index');
        }

        return view('booths.show', compact('booth'));
    }

    public function start(Booth $booth)
    {
        $setting = ElectionSetting::first();

        if (!$setting || $setting->status !== 'berlangsung') {
            return redirect()
                ->route('booths.show', $booth)
                ->with(
                    'error',
                    'Pemungutan suara belum dibuka atau sudah selesai.'
                );
        }

        if (
            $booth->status !== 'assigned'
            || !$booth->current_voter_id
        ) {
            return redirect()
                ->route('booths.show', $booth)
                ->with(
                    'error',
                    'Belum ada pemilih yang dikirim ke bilik ini.'
                );
        }

        $booth->update([
            'status' => 'voting',
            'voting_started_at' => now(),
        ]);

        session([
            'active_booth_id' => $booth->id,
            'verified_voter_id' => $booth->current_voter_id,
        ]);

        return redirect()->route('voting.index');
    }

    public function status()
    {
        $booths = Booth::query()
            ->with('currentVoter.dusun')
            ->orderBy('id')
            ->get()
            ->map(function (Booth $booth) {
                return [
                    'id' => $booth->id,
                    'name' => $booth->name,
                    'status' => $booth->status,
                    'voter_name' => $booth->currentVoter?->name,
                    'voter_code' => $booth->currentVoter?->voter_code,
                    'dusun' => $booth->currentVoter?->dusun?->name,
                    'rw' => $booth->currentVoter?->rw,
                    'rt' => $booth->currentVoter?->rt,
                ];
            });

        return response()->json($booths);
    }
}