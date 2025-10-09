<?php

namespace App\Providers;

use App\Listeners\SetUserStatusOnline;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;

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

        Event::listen(
            Login::class,
            [SetUserStatusOnline::class, 'handle']
        );
    }
}
