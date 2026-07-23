<?php

namespace App\Observers;

use App\Models\Ballot;
use App\Services\ActivityLogger;
use App\Enums\ActivityType;

class BallotObserver
{
    public function updated(Ballot $ballot): void
    {

        if (
            $ballot->wasChanged('is_counted')
            && $ballot->is_counted
        ) {

            ActivityLogger::log(
                ActivityType::SCAN,
                'Satu surat suara berhasil dihitung.'
            );

        }

    }
}
