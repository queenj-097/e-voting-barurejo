<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Ballot;
use App\Models\Booth;
use App\Models\Voter;

use App\Observers\BallotObserver;
use App\Observers\BoothObserver;
use App\Observers\VoterObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Ballot::observe(BallotObserver::class);

        Booth::observe(BoothObserver::class);

        Voter::observe(VoterObserver::class);
    }
}