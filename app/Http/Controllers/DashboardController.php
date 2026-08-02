<?php

namespace App\Http\Controllers;

use App\Models\Ballot;
use App\Models\Booth;
use App\Models\Candidate;
use App\Models\ElectionGroup;
use App\Models\ElectionSetting;
use App\Models\Voter;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view(
            'dashboard.index',
            $this->getDashboardData()
        );
    }

    public function live(): JsonResponse
    {
        $setting = ElectionSetting::first();

        $candidateScope = $setting?->candidate_scope ?? 'general';

        $totalVoters = Voter::query()->count();

        $votedVoters = Voter::query()
            ->where('has_voted', true)
            ->count();

        $notVotedVoters = Voter::query()
            ->where('has_voted', false)
            ->count();

        $totalBallots = Ballot::query()->count();

        $countedBallots = Ballot::query()
            ->where('is_counted', true)
            ->count();

        $uncountedBallots = Ballot::query()
            ->where('is_counted', false)
            ->count();

        $participationPercentage = $totalVoters > 0
            ? round(
                ($votedVoters / $totalVoters) * 100,
                1
            )
            : 0;

        /*
         * Data general tetap dikirim supaya dashboard
         * mode kandidat umum tetap dapat digunakan.
         */
        $generalResults = $this->getGeneralResults();

        /*
         * Data kelompok dipakai saat candidate_scope = grouped.
         */
        $groupedResults = $candidateScope === 'grouped'
            ? $this->getGroupedResults()
            : collect();

        return response()->json([
            'candidate_scope' => $candidateScope,

            'total_voters' => $totalVoters,
            'voted' => $votedVoters,
            'not_voted' => $notVotedVoters,

            'ballots' => $totalBallots,
            'counted' => $countedBallots,
            'uncounted' => $uncountedBallots,

            'participation_percentage' =>
                $participationPercentage,

            /*
             * Untuk mode general.
             */
            'candidates' => $generalResults['candidates'],
            'temporary_winner' =>
                $generalResults['temporary_winner'],

            /*
             * Untuk mode grouped.
             */
            'groups' => $groupedResults->values(),
        ]);
    }

    private function getDashboardData(): array
    {
        $setting = ElectionSetting::first();

        $candidateScope = $setting?->candidate_scope ?? 'general';

        $totalVoters = Voter::query()->count();

        $votedVoters = Voter::query()
            ->where('has_voted', true)
            ->count();

        $notVotedVoters = Voter::query()
            ->where('has_voted', false)
            ->count();

        $totalCandidates = Candidate::query()->count();

        $totalBallots = Ballot::query()->count();

        $countedBallots = Ballot::query()
            ->where('is_counted', true)
            ->count();

        $uncountedBallots = Ballot::query()
            ->where('is_counted', false)
            ->count();

        $participationPercentage = $totalVoters > 0
            ? round(
                ($votedVoters / $totalVoters) * 100,
                1
            )
            : 0;

        $generalResults = $this->getGeneralResults();

        /*
         * Variabel lama tetap disediakan supaya halaman
         * dashboard tidak langsung error sebelum Blade
         * versi baru dipasang.
         */
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

        $groupedResults = $candidateScope === 'grouped'
            ? $this->getGroupedResults()
            : collect();

        $booths = Booth::query()
            ->with('currentVoter')
            ->orderBy('id')
            ->get();

        return compact(
            'setting',
            'candidateScope',
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
            'groupedResults',
            'booths'
        );
    }

    /**
     * Membuat data hasil untuk mode kandidat umum.
     */
    private function getGeneralResults(): array
    {
        $countedBallots = Ballot::query()
            ->where('is_counted', true)
            ->count();

        $candidates = Candidate::query()
            ->withCount([
                'ballots as counted_votes' => function ($query) {
                    $query->where('is_counted', true);
                },
            ])
            ->orderByDesc('counted_votes')
            ->orderBy('number')
            ->get()
            ->map(function (Candidate $candidate) use (
                $countedBallots
            ) {
                return $this->formatCandidate(
                    $candidate,
                    $countedBallots
                );
            })
            ->values();

        $temporaryWinner = $countedBallots > 0
            ? $candidates->first()
            : null;

        return [
            'candidates' => $candidates,
            'temporary_winner' => $temporaryWinner,
        ];
    }

    /**
     * Membuat hasil per kelompok pemilihan.
     */
    private function getGroupedResults()
    {
        $groups = ElectionGroup::query()
            ->with([
                'candidates' => function ($query) {
                    $query
                        ->with('dusuns')
                        ->withCount([
                            'ballots as counted_votes' =>
                                function ($ballotQuery) {
                                    $ballotQuery->where(
                                        'is_counted',
                                        true
                                    );
                                },
                        ])
                        ->orderBy('number');
                },

                'voters.dusun',
            ])
            ->orderBy('id')
            ->get();

        return $groups->map(function (
            ElectionGroup $group,
            int $index
        ) {
            /*
             * Jumlah surat suara sah dalam kelompok.
             */
            $groupCountedBallots = $group->candidates
                ->sum('counted_votes');

            /*
             * Ambil daftar dusun dari kandidat dan pemilih.
             * Digabung agar nama dusun tetap muncul meskipun
             * belum ada suara atau kandidat belum lengkap.
             */
            $candidateDusuns = $group->candidates
                ->flatMap(function (Candidate $candidate) {
                    return $candidate->dusuns;
                });

            $voterDusuns = $group->voters
                ->pluck('dusun')
                ->filter();

            $dusuns = $candidateDusuns
                ->merge($voterDusuns)
                ->unique('id')
                ->sortBy('name')
                ->values();

            $formattedCandidates = $group->candidates
                ->map(function (Candidate $candidate) use (
                    $groupCountedBallots
                ) {
                    return $this->formatCandidate(
                        $candidate,
                        $groupCountedBallots
                    );
                })
                ->sortByDesc('counted_votes')
                ->values();

            $temporaryWinner = $groupCountedBallots > 0
                ? $formattedCandidates->first()
                : null;

            $totalVoters = $group->voters->count();

            $votedVoters = $group->voters
                ->where('has_voted', true)
                ->count();

            $participationPercentage = $totalVoters > 0
                ? round(
                    ($votedVoters / $totalVoters) * 100,
                    1
                )
                : 0;

            return [
                'id' => $group->id,

                'name' => $group->name
                    ?: 'Kelompok Pemilihan ' . ($index + 1),

                'dusuns' => $dusuns
                    ->pluck('name')
                    ->values(),

                'dusun_text' => $dusuns->isNotEmpty()
                    ? $dusuns->pluck('name')->join(', ')
                    : 'Belum ada dusun yang ditetapkan',

                'total_voters' => $totalVoters,
                'voted_voters' => $votedVoters,
                'not_voted_voters' =>
                    max($totalVoters - $votedVoters, 0),

                'participation_percentage' =>
                    $participationPercentage,

                'counted_ballots' => $groupCountedBallots,

                'temporary_winner' => $temporaryWinner,

                'candidates' => $formattedCandidates,
            ];
        });
    }

    /**
     * Menyeragamkan struktur data kandidat
     * untuk Blade dan endpoint live.
     */
    private function formatCandidate(
        Candidate $candidate,
        int $totalCountedBallots
    ): array {
        $countedVotes = (int) $candidate->counted_votes;

        $percentage = $totalCountedBallots > 0
            ? round(
                ($countedVotes / $totalCountedBallots) * 100,
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

            'counted_votes' => $countedVotes,
            'percentage' => $percentage,
        ];
    }
}