<?php

namespace App\Providers;

use App\Models\Mutation;
use App\Models\Withdrawal;
use App\Observers\MutationObserver;
use App\Observers\WithdrawalObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // Force HTTPS for tunneling in local environment
        if (env(key: 'APP_ENV') === 'local' && request()->server(key: 'HTTP_X_FORWARDED_PROTO') === 'https') {
            URL::forceScheme(scheme: 'https');
        }

        // Observers
        Mutation::observe(MutationObserver::class);
        Withdrawal::observe(WithdrawalObserver::class);
    }
}
