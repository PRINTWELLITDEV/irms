<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\RsUser;

class UpdateRsUserLastSeen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // app/Http/Middleware/UpdateRsUserLastSeen.php

    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // **Optional:** Assuming your model has the $casts fix (last_seen_at => 'datetime')
            if ($user instanceof RsUser) {

                // This is the core logic.
                // If last_seen_at is NULL OR 1 full minute has passed, update it.
                $shouldUpdate = !$user->last_seen_at || $user->last_seen_at->diffInMinutes(now()) >= 1;

                if ($shouldUpdate) {
                    // Use the explicit composite key update query (your fix)
                    RsUser::where('rssite', $user->rssite)
                        ->where('userid', $user->userid)
                        ->update(['last_seen_at' => now()]);

                    // Update the in-memory model to avoid stale data during the rest of the request
                    $user->last_seen_at = now();
                }
            }
        }
        return $next($request);
    }
}
