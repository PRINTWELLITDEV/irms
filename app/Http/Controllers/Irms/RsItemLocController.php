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
            $itemlocs = \DB::select('EXEC sp_view_rsitemlocs @rssite = ?', [null]);
        } else {
            // Other users see only their site
            $itemlocs = \DB::select('EXEC sp_view_rsitemlocs @rssite = ?', [$userSite]);
        }

        $sites = IrmsSite::all();
        return view('irms.irms-layouts.item-locations', compact('itemlocs', 'sites'));
    }

    // public function jobDetails(Request $request)
    // {
    //     $job = $request->input('job');
    //     $rssite = $request->input('rssite');

    //     // $details = \DB::table('rsitemloc')
    //     //     ->select(
    //     //         'rssite',
    //     //         'rspallet_num',
    //     //         'job',
    //     //         'rsloc',
    //     //         'qty',
    //     //         'um',
    //     //         'datercvd',
    //     //         'createdby AS rcvd_by'
    //     //     )
    //     //     ->where('job', $job)
    //     //     ->when($rssite, function ($query) use ($rssite) {
    //     //         $query->where('rssite', $rssite);
    //     //     })
    //     //     ->orderBy('job')
    //     //     ->orderBy('rsloc')
    //     //     ->get();

    //     $details = \DB::select('EXEC sp_job_details @job = ?, @rssite = ?', [$job, $rssite]);

    //     return response()->json($details);
    // }

    public function showJobDetails($job, Request $request)
    {
        $user = auth()->user();
        $rssite = $user->level == 1 ? null : $user->rssite;

        // Get summary info for the job (first row)
        $summary = \DB::selectOne('EXEC sp_view_rsitemlocs @job = ?, @rssite = ?', [$job, $rssite]);
        // Get rack list for the job
        $details = \DB::select('EXEC sp_job_details @job = ?, @rssite = ?', [$job, $rssite]);

        return view('irms.irms-layouts.item-job-details', compact('summary', 'details', 'job'));
    }
}
