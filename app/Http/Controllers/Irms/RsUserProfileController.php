<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use App\Models\IrmsSite;
use App\Models\RsUser;

class RsUserProfileController extends Controller
{
    public function show($userid)
    {
        $user = RsUser::where('userid', $userid)->firstOrFail();
        $site = IrmsSite::where('rssite', $user->rssite)->first();
        $siteDesc = $site ? $site->rssite_desc : $user->rssite;
        return view('irms.irms-layouts.user-profile', compact('user', 'siteDesc'));
    }
}
