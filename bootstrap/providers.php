<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\FortifyServiceProvider::class,
    App\Providers\JetstreamServiceProvider::class,
    App\Providers\MenuServiceProvider::class,
    App\Providers\SettingsServiceProvider::class,
    Spatie\Permission\PermissionServiceProvider::class,
    App\Providers\SettingsServiceProvider::class,
    Berkayk\OneSignal\OneSignalServiceProvider::class,
    Laravel\Socialite\SocialiteServiceProvider::class,

];
