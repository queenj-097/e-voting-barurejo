<?php

namespace App\Http\Controllers;

use App\Models\Booth;
use Illuminate\Http\Request;
use App\Models\ElectionSetting;

class BoothController extends Controller
{
    public function show(Booth $booth)
    {
        $booth->load('currentVoter');

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
            ->with('currentVoter')
            ->orderBy('id')
            ->get()
            ->map(function (Booth $booth) {
                return [
                    'id' => $booth->id,
                    'name' => $booth->name,
                    'status' => $booth->status,
                    'voter_name' => $booth->currentVoter?->name,
                    'dpt_number' => $booth->currentVoter?->dpt_number,
                ];
            });

        return response()->json($booths);
    }
}