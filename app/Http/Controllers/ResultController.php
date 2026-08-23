<?php

namespace App\Http\Controllers;

use App\Models\Ballot;
use App\Models\Candidate;
use App\Models\ElectionSetting;
use App\Models\Voter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResultController extends Controller
{
    public function index(): View
    {
        return view(
            'results.index',
            $this->getResultData()
        );
    }

    public function exportPdf()
    {
        $data = $this->getResultData();

        $pdf = Pdf::loadView('results.pdf', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download(
            'rekapitulasi-e-voting-' .
            now()->format('Y-m-d-His') .
            '.pdf'
        );
    }

    /**
     * Menampilkan layar live count.
     */
    public function liveCount(): View
    {
        $setting = ElectionSetting::query()->first();

        return view(
            'results.live-count',
            compact('setting')
        );
    }

    /**
     * Mengirim data live count berdasarkan sesi.
     *
     * Sesi 1: Seneposari
     * Sesi 2: Senepolor dan Krajan
     * Sesi 3: Sumberurip dan Sumbermanggis
     */
    public function liveCountData(
        Request $request
    ): JsonResponse {
        $session = (string) $request->query(
            'session', ''
        );

        $sessionDusuns = [
            '1' => [
                'SENEPOSARI',
            ],

            '2' => [
                'SENEPOLOR',
                'KRAJAN',
            ],

            '3' => [
                'SUMBERURIP',
                'SUMBERMANGGIS',
            ],
        ];

        $activeDusuns = $session
            ? ($sessionDusuns[$session] ?? [])
            : array_merge(...array_values($sessionDusuns));

        /*
         * Kandidat yang termasuk dalam sesi aktif.
         */
        $candidates = Candidate::query()
            ->with('dusuns')
            ->withCount([
                'ballots as counted_votes' => function ($query) {
                    $query->where(
                        'is_counted',
                        true
                    );
                },
            ])
            ->whereHas(
                'dusuns',
                function ($query) use ($activeDusuns) {
                    $query->whereIn(
                        'dusuns.name',
                        $activeDusuns
                    );
                }
            )
            ->get();

        $candidateIds = $candidates
            ->pluck('id')
            ->values();

        /*
         * Ringkasan DPT hanya untuk dusun dalam sesi aktif.
         */
        $totalVoters = Voter::query()
            ->whereHas(
                'dusun',
                function ($query) use ($activeDusuns) {
                    $query->whereIn(
                        'name',
                        $activeDusuns
                    );
                }
            )
            ->count();

        $votedVoters = Voter::query()
            ->whereHas(
                'dusun',
                function ($query) use ($activeDusuns) {
                    $query->whereIn(
                        'name',
                        $activeDusuns
                    );
                }
            )
            ->where(
                'has_voted',
                true
            )
            ->count();

        /*
         * Surat suara sesi dihitung berdasarkan kandidat
         * yang berada dalam dusun sesi aktif.
         */
        $totalBallots = Ballot::query()
            ->whereIn(
                'candidate_id',
                $candidateIds
            )
            ->count();

        $countedBallots = Ballot::query()
            ->whereIn(
                'candidate_id',
                $candidateIds
            )
            ->where(
                'is_counted',
                true
            )
            ->count();

        $uncountedBallots = Ballot::query()
            ->whereIn(
                'candidate_id',
                $candidateIds
            )
            ->where(
                'is_counted',
                false
            )
            ->count();

        $participationPercentage = $totalVoters > 0
            ? round(
                ($votedVoters / $totalVoters) * 100,
                1
            )
            : 0;

        /*
         * Kandidat dikelompokkan per dusun aktif.
         */
        $groups = collect($activeDusuns)
            ->map(function (
                string $dusunName
            ) use ($candidates) {
                $dusunCandidates = $candidates
                    ->filter(function (
                        Candidate $candidate
                    ) use ($dusunName) {
                        return $candidate
                            ->dusuns
                            ->contains(function ($dusun) use (
                                $dusunName
                            ) {
                                return strtoupper(
                                    trim($dusun->name)
                                ) === strtoupper(
                                    trim($dusunName)
                                );
                            });
                    });

                $totalDusunVotes = $dusunCandidates
                    ->sum('counted_votes');

                $sortedCandidates = $dusunCandidates
                    ->sort(function (
                        Candidate $candidateA,
                        Candidate $candidateB
                    ) {
                        if (
                            $candidateA->counted_votes
                            === $candidateB->counted_votes
                        ) {
                            return $candidateA->number
                                <=> $candidateB->number;
                        }

                        return $candidateB->counted_votes
                            <=> $candidateA->counted_votes;
                    })
                    ->values()
                    ->map(function (
                        Candidate $candidate
                    ) use ($totalDusunVotes) {
                        $percentage = $totalDusunVotes > 0
                            ? round(
                                (
                                    $candidate->counted_votes
                                    / $totalDusunVotes
                                ) * 100,
                                2
                            )
                            : 0;

                        return [
                            'id' =>
                                $candidate->id,

                            'number' =>
                                $candidate->number,

                            'name' =>
                                $candidate->name,

                            'photo_url' =>
                                $candidate->photo
                                    ? asset(
                                        'storage/' .
                                        $candidate->photo
                                    )
                                    : null,

                            'votes' =>
                                $candidate->counted_votes,

                            'percentage' =>
                                $percentage,
                        ];
                    })
                    ->all();

                return [
                    'dusun' =>
                        $dusunName,

                    'total_votes' =>
                        $totalDusunVotes,

                    'candidates' =>
                        $sortedCandidates,
                ];
            })
            ->values()
            ->all();

        /*
         * Suara terbaru hanya dari sesi aktif.
         */
        $latestBallot = Ballot::query()
            ->with([
                'candidate.dusuns',
            ])
            ->whereIn(
                'candidate_id',
                $candidateIds
            )
            ->where(
                'is_counted',
                true
            )
            ->whereNotNull(
                'counted_at'
            )
            ->latest(
                'counted_at'
            )
            ->first();

        $latestVote = null;

        if (
            $latestBallot
            && $latestBallot->candidate
        ) {
            $latestVote = [
                'ballot_id' =>
                    $latestBallot->id,

                'candidate_number' =>
                    $latestBallot
                        ->candidate
                        ->number,

                'candidate_name' =>
                    $latestBallot
                        ->candidate
                        ->name,

                'candidate_photo_url' =>
                    $latestBallot
                        ->candidate
                        ->photo
                            ? asset(
                                'storage/' .
                                $latestBallot
                                    ->candidate
                                    ->photo
                            )
                            : null,

                'dusuns' =>
                    $latestBallot
                        ->candidate
                        ->dusuns
                        ->pluck('name')
                        ->values()
                        ->all(),

                'counted_at' =>
                    $latestBallot
                        ->counted_at
                        ?->format(
                            'd-m-Y H:i:s'
                        ),

                'counted_at_timestamp' =>
                    $latestBallot
                        ->counted_at
                        ?->timestamp,
            ];
        }

        return response()->json([
            'session' =>
                $session,

            'active_dusuns' =>
                $activeDusuns,

            'summary' => [
                'total_voters' =>
                    $totalVoters,

                'voted_voters' =>
                    $votedVoters,

                'total_ballots' =>
                    $totalBallots,

                'counted_ballots' =>
                    $countedBallots,

                'uncounted_ballots' =>
                    $uncountedBallots,

                'participation_percentage' =>
                    $participationPercentage,
            ],

            'groups' =>
                $groups,

            'latest_vote' =>
                $latestVote,

            'updated_at' =>
                now()->format(
                    'd-m-Y H:i:s'
                ),
        ]);
    }

    /**
     * Data rekap seluruh dusun.
     *
     * Dipakai oleh halaman rekap biasa dan PDF.
     * Bagian ini tidak difilter berdasarkan sesi.
     */
    private function getResultData(): array
    {
        $setting = ElectionSetting::query()->first();

        $candidates = Candidate::query()
            ->with('dusuns')
            ->withCount([
                'ballots as counted_votes' => function ($query) {
                    $query->where(
                        'is_counted',
                        true
                    );
                },
            ])
            ->get();

        /*
         * Kandidat dikelompokkan berdasarkan relasi dusun.
         * Satu kandidat bisa masuk lebih dari satu kelompok.
         */
        $candidateGroups = collect();

        foreach ($candidates as $candidate) {
            if ($candidate->dusuns->isEmpty()) {
                $candidateGroups
                    ->getOrPut(
                        'Dusun Tidak Diketahui',
                        collect()
                    )
                    ->push($candidate);

                continue;
            }

            foreach ($candidate->dusuns as $dusun) {
                $candidateGroups
                    ->getOrPut(
                        $dusun->name,
                        collect()
                    )
                    ->push($candidate);
            }
        }

        $candidatesByDusun = $candidateGroups
            ->map(function ($dusunCandidates) {
                $totalDusunVotes = $dusunCandidates
                    ->sum('counted_votes');

                $sortedCandidates = $dusunCandidates
                    ->sort(function (
                        Candidate $candidateA,
                        Candidate $candidateB
                    ) {
                        if (
                            $candidateA->counted_votes
                            === $candidateB->counted_votes
                        ) {
                            return $candidateA->number
                                <=> $candidateB->number;
                        }

                        return $candidateB->counted_votes
                            <=> $candidateA->counted_votes;
                    })
                    ->values()
                    ->map(function (
                        Candidate $candidate
                    ) use ($totalDusunVotes) {
                        $candidate->percentage =
                            $totalDusunVotes > 0
                                ? round(
                                    (
                                        $candidate->counted_votes
                                        / $totalDusunVotes
                                    ) * 100,
                                    2
                                )
                                : 0;

                        return $candidate;
                    });

                return [
                    'total_votes' =>
                        $totalDusunVotes,

                    'candidates' =>
                        $sortedCandidates,
                ];
            })
            ->sortKeys();

        $totalVoters = Voter::query()
            ->count();

        $votedVoters = Voter::query()
            ->where(
                'has_voted',
                true
            )
            ->count();

        $totalBallots = Ballot::query()
            ->count();

        $countedBallots = Ballot::query()
            ->where(
                'is_counted',
                true
            )
            ->count();

        $uncountedBallots = Ballot::query()
            ->where(
                'is_counted',
                false
            )
            ->count();

        $participationPercentage = $totalVoters > 0
            ? round(
                ($votedVoters / $totalVoters) * 100,
                1
            )
            : 0;

        return compact(
            'setting',
            'candidatesByDusun',
            'totalVoters',
            'votedVoters',
            'totalBallots',
            'countedBallots',
            'uncountedBallots',
            'participationPercentage'
        );
    }
}