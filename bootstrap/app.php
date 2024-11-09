<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\LocaleMiddleware;
use App\Http\Middleware\UpdateUserLastActivity;
use App\Http\Middleware\SwitchDatabase;
use App\Http\Middleware\SetDatabaseMiddleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Routing\Middleware\SubstituteBindings;
use App\Http\Middleware\VerifyCsrfToken;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    api: __DIR__ . '/../routes/api.php',
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware) {
    // Middleware للواجهة الأمامية (web)
    $middleware->web(LocaleMiddleware::class);
    $middleware->web(UpdateUserLastActivity::class);
    $middleware->web(SwitchDatabase::class);
	 $middleware->web(VerifyCsrfToken::class);

    // Middleware للـ API
    $middleware->appendToGroup('api', [
        EnsureFrontendRequestsAreStateful::class,
        'throttle:api',
        SubstituteBindings::class,
    ]);
  })
  ->withExceptions(function (Exceptions $exceptions) {
  })->create();
