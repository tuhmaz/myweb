<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;

class SwitchDatabase
{

    public function handle($request, Closure $next)
    {
        $selectedCountry = $request->input('country');

        switch ($selectedCountry) {
            case 'sa':
                Config::set('database.default', 'sa');
                session(['country' => 'sa']); // تخزين الدولة
                break;
            case 'eg':
                Config::set('database.default', 'eg');
                session(['country' => 'eg']); // تخزين الدولة
                break;
            case 'ps':
                Config::set('database.default', 'ps');
                session(['country' => 'ps']); // تخزين الدولة
                break;
            default:
                Config::set('database.default', 'jo');
                session(['country' => 'jo']);  // إزالة الدولة
                break;
        }

        return $next($request);
    }
}
