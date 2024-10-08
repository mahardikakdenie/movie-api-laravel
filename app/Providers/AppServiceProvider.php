<?php

namespace App\Providers;

use App\Http\Interface\AuthRepositoryInterface;
use App\Http\Interface\AuthServiceInterface;
use App\Http\Interface\MovieRepositoryInterface;
use App\Http\Interface\MovieServiceInterface;
use App\Http\Repository\AuthRepository;
use App\Http\Repository\MovieRepository;
use App\Http\Service\AuthService;
use App\Http\Service\MovieService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Repository
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(MovieRepositoryInterface::class, MovieRepository::class);

        // Service
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(MovieServiceInterface::class, MovieService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
