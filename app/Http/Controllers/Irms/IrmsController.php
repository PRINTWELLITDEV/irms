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
            // abort(403, 'Unauthorized');
            return response()->view('irms.irms-layouts.home', [], 403);
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
        return $site ? $site->logo_pic_url : null;
    }
    public static function getprofile()
    {
        $userid = Auth::user()->userid ?? null;
        $user = RsUser::where('userid', $userid)->first();
        return $user ? $user->profile_pic_url : null;
    }
}
