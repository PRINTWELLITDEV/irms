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
        if (auth()->user()->level > 3) {
            abort(401, 'Unauthorized');
        }
        $user = auth()->user();
        $userSite = $user->rssite;
        $userid = $user->userid;

        // if ($userid === 'sa') {
        //     $itemlocs = \DB::select('EXEC sp_view_rsitemlocs', [null]);
        // } else {
        //     $itemlocs = \DB::select('EXEC sp_view_rsitemlocs ?', [$userSite]);
        // }
        if (auth()->user()->level == 1) {
            // Admins see all sites
            $itemlocs = DB::table('rsitemloc')
                ->select(
                    'rssite',
                    'rswhse',
                    'rsbaynum',
                    'job',
                    'item',
                    'desc',
                    DB::raw('SUM(qty) AS totalqty'),
                    'um'
                )
                ->whereNotNull('job')
                ->groupBy('rssite', 'rswhse', 'rsbaynum', 'job', 'item', 'desc', 'um')
                ->orderBy('job')
                ->get();
        } else {
            // Other users see only their site
            $itemlocs = DB::table('rsitemloc')
                ->select(
                    'rssite',
                    'rswhse',
                    'rsbaynum',
                    'job',
                    'item',
                    'desc',
                    DB::raw('SUM(qty) AS totalqty'),
                    'um'
                )
                ->where('rssite', $userSite)
                ->whereNotNull('job')
                ->groupBy('rssite', 'rswhse', 'rsbaynum', 'job', 'item', 'desc', 'um')
                ->orderBy('job')
                ->get();
        }

        $sites = IrmsSite::all();
        return view('irms.irms-layouts.item-locations', compact('itemlocs', 'sites'));
    }

    public function jobDetails(Request $request)
    {
        $job = $request->input('job');
        $rssite = $request->input('rssite');

        $details = \DB::table('rsitemloc')
            ->select(
                'rssite',
                'rspallet_num',
                'job',
                'rsloc',
                'qty',
                'um',
                'datercvd',
                'createdby AS rcvd_by'
            )
            ->where('job', $job)
            ->when($rssite, function ($query) use ($rssite) {
                $query->where('rssite', $rssite);
            })
            ->orderBy('job')
            ->orderBy('rsloc')
            ->get();

        return response()->json($details);
    }
}
