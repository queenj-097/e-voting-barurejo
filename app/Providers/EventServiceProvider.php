<?php

namespace App\Providers;

use App\Events\UserLoggedIn;
use App\Events\UserLoggedOut;
use App\Listeners\LogUserLogin;
use App\Listeners\LogUserLogout;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [

        UserLoggedIn::class => [
            LogUserLogin::class,
        ],

        UserLoggedOut::class => [
            LogUserLogout::class,
        ],

    ];
}