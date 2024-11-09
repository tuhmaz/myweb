<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // You can register bindings or services here if needed.
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Check if the settings table exists before attempting to load settings
        if (Schema::hasTable('settings')) {
            // Load settings from the database
            $settings = Setting::all();

            // Store each setting in the config
            foreach ($settings as $setting) {
                config()->set('settings.' . $setting->key, $setting->value);
            }
        }
    }
}
