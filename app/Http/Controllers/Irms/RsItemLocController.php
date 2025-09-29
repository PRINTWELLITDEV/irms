<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\IrmsSite; 
use App\Models\RsUser;
use App\Models\RsItemLoc;

class RsItemLocController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userSite = $user->rssite;
        $userid = $user->userid;

        if ($userid === 'sa') {
            $itemlocs = \DB::select('EXEC sp_view_rsitemlocs', [null]);
        } else {
            $itemlocs = \DB::select('EXEC sp_view_rsitemlocs ?', [$userSite]);
        }

        $sites = IrmsSite::all();
        return view('irms.irms-layouts.item-locations', compact('itemlocs', 'sites'));
    }
}
