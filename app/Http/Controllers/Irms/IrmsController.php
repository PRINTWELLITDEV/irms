<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use App\Models\IrmsSite;
use Illuminate\Support\Facades\Auth;

class IrmsController extends Controller
{
    public static function getSiteDesc()
    {
        $rssite = Auth::user()->rssite ?? null;
        $site = IrmsSite::where('rssite', $rssite)->first();
        return $site ? $site->rssite_desc : ($rssite ?? 'IRMS');
    }
}
