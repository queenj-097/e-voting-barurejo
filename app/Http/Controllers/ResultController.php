<?php

namespace App\Http\Controllers;

use App\Models\Ballot;
use App\Models\Candidate;

class ResultController extends Controller
{
    public function index()
    {
        $candidates = Candidate::query()
            ->withCount([
                'ballots as counted_votes' => function ($query) {
                    $query->where('is_counted', true);
                },
            ])
            ->orderBy('number')
            ->get();

        $totalBallots = Ballot::count();
        $countedBallots = Ballot::where('is_counted', true)->count();
        $uncountedBallots = Ballot::where('is_counted', false)->count();

        return view('results.index', compact(
            'candidates',
            'totalBallots',
            'countedBallots',
            'uncountedBallots'
        ));
    }
}