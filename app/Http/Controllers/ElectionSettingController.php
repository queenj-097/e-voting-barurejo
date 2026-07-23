<?php

namespace App\Http\Controllers;

use App\Models\ElectionSetting;
use Illuminate\Http\Request;
use App\Models\Ballot;
use App\Models\Booth;
use App\Models\Voter;
use Illuminate\Support\Facades\DB;

class ElectionSettingController extends Controller
{
    public function edit()
    {
        $setting = ElectionSetting::firstOrCreate([]);

        return view('settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'institution' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'election_date' => ['nullable', 'date'],
            'status' => ['required', 'in:persiapan,berlangsung,selesai'],
        ]);

        $setting = ElectionSetting::firstOrCreate([]);
        $setting->update($validated);

        return redirect()
            ->route('settings.edit')
            ->with('success', 'Pengaturan pemilihan berhasil diperbarui.');
    }

    public function resetElection()
    {
        DB::transaction(function () {
            Ballot::query()->delete();

            Voter::query()->update([
                'has_voted' => false,
                'voted_at' => null,
            ]);

            Booth::query()->update([
                'status' => 'idle',
                'current_voter_id' => null,
                'assigned_at' => null,
                'voting_started_at' => null,
            ]);
        });

        session()->forget([
            'verified_voter_id',
            'active_booth_id',
        ]);

        return redirect()
            ->route('settings.edit')
            ->with('success', 'Data pemilihan berhasil direset.');
    }
}