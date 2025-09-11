<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Laravel\Passport\Contracts\AuthorizationViewResponse;
use App\Http\Responses\AutoApproveAuthorizationResponse;
use Carbon\CarbonInterval;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthorizationViewResponse::class, AutoApproveAuthorizationResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::ignoreRoutes();
        Passport::enableImplicitGrant();
        Passport::tokensExpireIn(CarbonInterval::days(10));
        Passport::refreshTokensExpireIn(CarbonInterval::year(1));
    }
}
