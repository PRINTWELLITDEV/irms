<?php

namespace App\Providers;

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
        view()->composer('irms.irms-partials.nav', function ($view) {
            $user = auth()->user();
            $siteDesc = null;
            if ($user && $user->rssite) {
                $siteDesc = \DB::table('irms_site')
                    ->where('rssite', $user->rssite)
                    ->value('rssite_desc');
            }
            $view->with(['user' => $user, 'siteDesc' => $siteDesc]);
        });
    }
}
