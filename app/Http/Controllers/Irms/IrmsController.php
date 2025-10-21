<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\IrmsSite;
use App\Models\RsUser;

class IrmsController extends Controller
{
    public function index()
    {
        if (auth()->user()->level > 3) {
            // abort(401, 'Unauthorized');
            return response()->view('irms.irms-layouts.home', [], 401);
        }
        return view('irms.irms-layouts.dashboard');
    }
    public static function getSiteDesc()
    {
        $rssite = Auth::user()->rssite ?? null;
        $site = IrmsSite::where('rssite', $rssite)->first();
        return $site ? $site->rssite_desc : ($rssite ?? 'IRMS');
    }
    public static function getSiteImage()
    {
        $rssite = Auth::user()->rssite ?? null;
        $site = IrmsSite::where('rssite', $rssite)->first();
        // return $site ? $site->logo_pic_url : null;
        $logo_pic_url = $site ? $site->logo_pic_url : 'uploads/site-logo/nologo.png';
        if ($site && (!file_exists(public_path($site->logo_pic_url)) || !$site->logo_pic_url)) {
            $logo_pic_url = 'uploads/site-logo/nologo.png';
        } elseif (!$site) {
            $logo_pic_url = 'uploads/site-logo/nologo.png';
        } else {
            $logo_pic_url = $site->logo_pic_url;
        }

        return $logo_pic_url;
    }
    public static function getprofile()
    {
        $userid = Auth::user()->userid ?? null;
        $user = RsUser::where('userid', $userid)->first();
        $profile_pic_url = $user ? $user->profile_pic_url : 'uploads/user-profile/noprofile.png';
        if ($user && (!file_exists(public_path($user->profile_pic_url)) || !$user->profile_pic_url)) {
            $profile_pic_url = 'uploads/user-profile/noprofile.png';
        } elseif (!$user) {
            $profile_pic_url = 'uploads/user-profile/noprofile.png';
        } else {
            $profile_pic_url = $user->profile_pic_url;
        }

        return $profile_pic_url;
    }
}
