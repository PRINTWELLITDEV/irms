<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MarketingRestriction
{
/**
 * Function: Marketing Department Access Restriction
 *
 * Description:
 * Restricts Marketing users to the Item Inquiry module only.
 * All other IRMS routes are redirected to Item Inquiry.
 * Non-Marketing users retain their existing access.
 *
 * Author: Jim Dominic Pabalate
 * Date Created: September 28, 2026
 */
    public function handle(Request $request, Closure $next): Response
{
    $user = auth()->user();

    // If user is not logged in, continue normally
    if (!$user) {
        return $next($request);
    }

    // Check if user belongs to Marketing
    $isMarketing = strtolower(trim($user->department)) === 'marketing';

    if ($isMarketing) {

        // Allow Item Inquiry
        if ($request->is('irms/item-inquiry*')) {
            return $next($request);
        }

        // Block all other IRMS pages
        return redirect('/irms/item-inquiry');
    }

    // Non-Marketing users are not affected
    return $next($request);
}
}
