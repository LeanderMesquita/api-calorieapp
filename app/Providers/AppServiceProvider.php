<?php

namespace App\Providers;

use App\Listeners\DefaultMeals;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

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
        Passport::ignoreRoutes();
        Passport::hashClientSecrets();
        Passport::enablePasswordGrant();
        Passport::tokensExpireIn(now()->addDays(1));
        Passport::refreshTokensExpireIn(now()->addDays(7));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));

        Gate::policy(User::class, UserPolicy::class);


        Passport::tokensCan([
            'admin' => 'Manage users and perform admin tasks',
            'user' => 'Default user scope, to manage their profile, meals and entries'
        ]);
        Passport::setDefaultScope(['user']);


        Event::listen(
            Registered::class,
            DefaultMeals::class
        );
    }
}
