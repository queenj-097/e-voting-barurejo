<?php

namespace App\Http\Controllers;

use App\Models\Ballot;
use App\Models\Booth;
use App\Models\Candidate;
use App\Models\ElectionSetting;
use App\Models\Voter;

class DashboardController extends Controller
{
    public function index()
    {
        $data = $this->getDashboardData();

        return view('dashboard.index', $data);
    }

    public function live()
    {
        $totalVoters = Voter::count();
        $votedVoters = Voter::where('has_voted', true)->count();
        $notVotedVoters = Voter::where('has_voted', false)->count();

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
            ->get()
            ->map(function (Candidate $candidate) use ($countedBallots) {
                $percentage = $countedBallots > 0
                    ? round(
                        ($candidate->counted_votes / $countedBallots) * 100,
                        1
                    )
                    : 0;

                return [
                    'id' => $candidate->id,
                    'number' => $candidate->number,
                    'name' => $candidate->name,
                    'photo_url' => $candidate->photo
                        ? asset('storage/' . $candidate->photo)
                        : null,
                    'counted_votes' => $candidate->counted_votes,
                    'percentage' => $percentage,
                ];
            })
            ->values();

        $temporaryWinner = $countedBallots > 0
            ? $candidates->first()
            : null;

        return response()->json([
            'total_voters' => $totalVoters,
            'voted' => $votedVoters,
            'not_voted' => $notVotedVoters,
            'ballots' => $totalBallots,
            'counted' => $countedBallots,
            'uncounted' => $uncountedBallots,
            'participation_percentage' => $participationPercentage,
            'candidates' => $candidates,
            'temporary_winner' => $temporaryWinner,
        ]);
    }

    private function getDashboardData(): array
    {
        $setting = ElectionSetting::first();

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

        $booths = Booth::query()
            ->with('currentVoter')
            ->orderBy('id')
            ->get();

        return compact(
            'setting',
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
            'booths'
        );
    }
}