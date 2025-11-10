<?php

namespace App\Listeners;

use App\Events\UserStatusUpdated;
use App\Models\RsUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Login;

class SetUserStatusOnline
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;

        if ($user && $user instanceof RsUser) {
            RsUser::where('rssite', $user->rssite)
                ->where('userid', $user->userid)
                ->update([
                    'status' => 'online',
                    'last_seen_at' => now(),
                ]);
        }

        if ($user) {
            broadcast(new UserStatusUpdated($user->userid, 'online'));
            \Log::info("User {$user->userid} logged in and status set to online");
        }
        ;
    }
}
