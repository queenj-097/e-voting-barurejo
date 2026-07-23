<?php

namespace App\Http\Controllers;

use App\Models\Ballot;
use App\Models\Candidate;
use App\Models\Voter;
use App\Models\ElectionSetting;
use App\Models\Booth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalVoters = Voter::count();
        $votedVoters = Voter::where('has_voted', true)->count();
        $notVotedVoters = Voter::where('has_voted', false)->count();

        $totalCandidates = Candidate::count();

        $totalBallots = Ballot::count();
        $countedBallots = Ballot::where('is_counted', true)->count();
        $uncountedBallots = Ballot::where('is_counted', false)->count();

        $participationPercentage = $totalVoters > 0
            ? round(($votedVoters / $totalVoters) * 100, 1)
            : 0;

        $candidates = Candidate::query()
            ->withCount([
                'ballots as counted_votes' => function ($query) {
                    $query->where('is_counted', true);
                },
            ])
            ->orderByDesc('counted_votes')
            ->orderBy('number')
            ->get();

        $temporaryWinner = $countedBallots > 0
            ? $candidates->first()
            : null;

        $setting = ElectionSetting::first();

        $booths = Booth::query()
            ->with('currentVoter')
            ->orderBy('id')
            ->get();

        return view('dashboard.index', compact(
            'totalVoters',
            'votedVoters',
            'notVotedVoters',
            'totalCandidates',
            'totalBallots',
            'countedBallots',
            'uncountedBallots',
            'participationPercentage',
            'candidates',
            'temporaryWinner',
            'setting',
            'booths'
        ));
    }
}