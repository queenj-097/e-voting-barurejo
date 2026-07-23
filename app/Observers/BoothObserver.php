<?php

namespace App\Observers;

use App\Models\Booth;
use App\Services\ActivityLogger;
use App\Enums\ActivityType;

class BoothObserver
{
    public function updated(Booth $booth): void
    {

        if ($booth->wasChanged('status')) {

            ActivityLogger::log(
                ActivityType::BOOTH,
                "{$booth->name} berubah menjadi {$booth->status}."
            );

        }

    }
}