<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        Passport::ignoreRoutes();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));

        Passport::tokensCan([
            'access'      => 'Access data scope',
            'refresh'     => 'Refresh token scope',
            // Add more scopes as needed
        ]);

        Passport::setDefaultScope([
            'access',
            // 'refresh',
        ]);


        // Paginator::useBootstrap();
        Paginator::useBootstrapFive();
    }
}
