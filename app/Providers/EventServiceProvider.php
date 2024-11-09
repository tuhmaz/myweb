<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            'App\Listeners\UpdateUserStatus@handleLogin',
        ],
        Logout::class => [
            'App\Listeners\UpdateUserStatus@handleLogout',
        ],
    ];

    public function boot()
    {
        parent::boot();

        // Register any events for your application.
    }
}
