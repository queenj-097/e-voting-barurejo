<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\ElectionGroup;
use App\Models\ElectionSetting;
use App\Models\Voter;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AutoElectionGroupService
{
    public function sync(): void
    {
        $setting = ElectionSetting::query()->first();

        /*
         * Kalau mode kandidat umum, kelompok tidak digunakan.
         */
        if (($setting?->candidate_scope ?? 'general') !== 'grouped') {
            DB::transaction(function () {
                Candidate::query()->update([
                    'election_group_id' => null,
                ]);

                Voter::query()->update([
                    'election_group_id' => null,
                ]);

                ElectionGroup::query()->delete();
            });

            return;
        }

        $candidates = Candidate::query()
            ->with('dusuns:id,name')
            ->orderBy('number')
            ->get();

        /*
         * Kelompokkan kandidat berdasarkan kombinasi dusun
         * yang dipilih pada kandidat.
         */
        $candidateGroups = $candidates
            ->filter(function (Candidate $candidate) {
                return $candidate->dusuns->isNotEmpty();
            })
            ->groupBy(function (Candidate $candidate) {
                return $candidate->dusuns
                    ->pluck('id')
                    ->sort()
                    ->implode('-');
            });

        $this->ensureDusunsDoNotOverlap($candidateGroups);

        DB::transaction(function () use (
            $candidates,
            $candidateGroups
        ) {
            /*
             * Kosongkan relasi lama sebelum kelompok dibentuk ulang.
             */
            Candidate::query()->update([
                'election_group_id' => null,
            ]);

            Voter::query()->update([
                'election_group_id' => null,
            ]);

            ElectionGroup::query()->delete();

            $groupNumber = 1;

            foreach ($candidateGroups as $groupCandidates) {
                $firstCandidate = $groupCandidates->first();

                $dusuns = $firstCandidate->dusuns
                    ->sortBy('name')
                    ->values();

                $group = ElectionGroup::query()->create([
                    'name' => 'Kelompok Pemilihan ' . $groupNumber,
                    'type' => 'dusun',
                    'description' => $dusuns
                        ->pluck('name')
                        ->join(', '),
                    'is_active' => true,
                ]);

                Candidate::query()
                    ->whereIn(
                        'id',
                        $groupCandidates->pluck('id')
                    )
                    ->update([
                        'election_group_id' => $group->id,
                    ]);

                Voter::query()
                    ->whereIn(
                        'dusun_id',
                        $dusuns->pluck('id')
                    )
                    ->update([
                        'election_group_id' => $group->id,
                    ]);

                $groupNumber++;
            }

            /*
             * Kandidat tanpa dusun tetap tidak memiliki kelompok.
             */
            Candidate::query()
                ->whereIn(
                    'id',
                    $candidates
                        ->filter(function (Candidate $candidate) {
                            return $candidate->dusuns->isEmpty();
                        })
                        ->pluck('id')
                )
                ->update([
                    'election_group_id' => null,
                ]);
        });
    }

    /**
     * Mencegah satu dusun masuk ke dua kelompok berbeda.
     *
     * Contoh tidak valid:
     * Kandidat A: Krajan + Senepo Lor
     * Kandidat B: Krajan + Sumberurip
     *
     * Krajan akan memiliki dua kelompok sehingga sistem
     * tidak dapat menentukan kandidat yang benar untuk pemilih.
     */
    private function ensureDusunsDoNotOverlap(
        $candidateGroups
    ): void {
        $dusunGroupMap = [];

        foreach ($candidateGroups as $signature => $candidates) {
            $dusunIds = $candidates
                ->first()
                ->dusuns
                ->pluck('id');

            foreach ($dusunIds as $dusunId) {
                if (
                    isset($dusunGroupMap[$dusunId])
                    && $dusunGroupMap[$dusunId] !== $signature
                ) {
                    throw ValidationException::withMessages([
                        'dusun_ids' =>
                            'Satu dusun tidak boleh digunakan dalam '
                            . 'dua kelompok kandidat yang berbeda. '
                            . 'Samakan pilihan dusun kandidat dalam '
                            . 'kelompok tersebut.',
                    ]);
                }

                $dusunGroupMap[$dusunId] = $signature;
            }
        }
    }
}