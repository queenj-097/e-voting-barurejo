<?php

namespace App\Observers;

use App\Models\Voter;
use App\Services\ActivityLogger;
use App\Enums\ActivityType;

class VoterObserver
{
    public function updated(Voter $voter): void
    {

        if (
            $voter->wasChanged('has_voted')
            && $voter->has_voted
        ) {

            ActivityLogger::log(
                ActivityType::VOTING,
                "Pemilih DPT {$voter->dpt_number} telah menggunakan hak pilih."
            );

        }

    }
}