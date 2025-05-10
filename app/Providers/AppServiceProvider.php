<?php

namespace App\Providers;

use App\Contracts\ExchangeRateInterface;
use App\Contracts\WebServiceInterface;
use App\Repository\ExchangeRateRepository;
use App\Repository\WebServiceRepository;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(ExchangeRateInterface::class, ExchangeRateRepository::class);
        $this->app->bind(WebServiceInterface::class, WebServiceRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
