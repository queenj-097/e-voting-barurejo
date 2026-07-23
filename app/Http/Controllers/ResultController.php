<?php

namespace App\Http\Controllers;

use App\Models\Ballot;
use App\Models\Candidate;
use App\Models\ElectionSetting;
use App\Models\Voter;
use Barryvdh\DomPDF\Facade\Pdf;

class ResultController extends Controller
{
    public function index()
    {
        $data = $this->getResultData();

        return view('results.index', $data);
    }

    public function exportPdf()
    {
        $data = $this->getResultData();

        $pdf = Pdf::loadView('results.pdf', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download(
            'rekapitulasi-e-voting-' . now()->format('Y-m-d-His') . '.pdf'
        );
    }

    private function getResultData(): array
    {
        $setting = ElectionSetting::first();

        $candidates = Candidate::query()
            ->withCount([
                'ballots as counted_votes' => function ($query) {
                    $query->where('is_counted', true);
                },
            ])
            ->orderByDesc('counted_votes')
            ->orderBy('number')
            ->get();

        $totalVoters = Voter::count();
        $votedVoters = Voter::where('has_voted', true)->count();

        $totalBallots = Ballot::count();
        $countedBallots = Ballot::where('is_counted', true)->count();
        $uncountedBallots = Ballot::where('is_counted', false)->count();

        $participationPercentage = $totalVoters > 0
            ? round(($votedVoters / $totalVoters) * 100, 1)
            : 0;

        return compact(
            'setting',
            'candidates',
            'totalVoters',
            'votedVoters',
            'totalBallots',
            'countedBallots',
            'uncountedBallots',
            'participationPercentage'
        );
    }
}