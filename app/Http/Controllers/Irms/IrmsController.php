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
        $user = auth()->user();
        if ($user->level > 3 || $user->level == 0) {
            return redirect()->route('irms.userprofile', ['userid' => $user->userid]);
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
        $logo_pic_url = $site ? $site->logo_pic_url : 'uploads/sites-img/no-logo.png';
        if (!file_exists(public_path($logo_pic_url)) || !$logo_pic_url) {
            $logo_pic_url = 'uploads/sites-img/no-logo.png';
        }
        return $logo_pic_url;
    }
}
