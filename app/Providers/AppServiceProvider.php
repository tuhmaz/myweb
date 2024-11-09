<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Laravel\Passport\Passport;
use App\Models\Article;
use App\Observers\ArticleObserver;
use App\Models\News;
use App\Observers\NewsObserver;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    // Load Passport keys
    Passport::loadKeysFrom(__DIR__ . '/../secrets/oauth');

    // Custom Vite styles
    Vite::useStyleTagAttributes(function (?string $src, string $url, ?array $chunk, ?array $manifest) {
      if ($src !== null) {
        return [
          'class' => preg_match("/(resources\/assets\/vendor\/scss\/(rtl\/)?core)-?.*/i", $src) ? 'template-customizer-core-css' : (preg_match("/(resources\/assets\/vendor\/scss\/(rtl\/)?theme)-?.*/i", $src) ? 'template-customizer-theme-css' : '')
        ];
      }
      return [];
    });

    // Register the observer for Article model
    Article::observe(ArticleObserver::class);

    // Register the observer for News model
    News::observe(NewsObserver::class);

    // Define Rate Limiter for API
    RateLimiter::for('api', function ($request) {
      return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
    });
  }
}
