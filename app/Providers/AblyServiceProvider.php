<?php

namespace App\Providers;

use Ably\AblyRest;
use Illuminate\Support\ServiceProvider;

class AblyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AblyRest::class, function ($app) {
            $ablyKey = env('ABLY_KEY');
            return new AblyRest($ablyKey);
        });
    }
}
