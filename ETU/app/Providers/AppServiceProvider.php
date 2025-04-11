<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\Eloquent\FilmRepository;
use App\Repository\FilmRepositoryInterface;
use App\Repository\Eloquent\CriticRepository;
use App\Repository\CriticRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(FilmRepositoryInterface::class, FilmRepository::class);
        $this->app->bind(CriticRepositoryInterface::class, CriticRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
