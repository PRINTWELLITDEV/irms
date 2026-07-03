<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\IrmsSite;
use App\Models\RsUser;
use App\Models\RsItemLoc;

class RsJobLocController extends Controller
{
    public function index()
    {
        if (auth()->user()->level > 3) {
            abort(401, 'Unauthorized');
        }

        $sites = IrmsSite::all();
        return view('irms.irms-layouts.job-locations', compact('sites'));

    }

    public function jobList(Request $request)
    {
        $user = auth()->user();
        $userSite = $user->rssite;

        if (auth()->user()->level == 1) {
            $joblocs = \DB::select('EXEC sp_job_summary @job = ?, @rssite = ?', [null, null]);
        } else {
            $joblocs = \DB::select('EXEC sp_job_summary @job = ?, @rssite = ?', [null, $userSite]);
        }

        return view('irms.irms-tables.job-list', compact('joblocs'))->render();
    }

    
   /* public function showJobDetails($job, Request $request)
    {
        $user = auth()->user();
        $rssite = $user->level == 1 ? null : $user->rssite;

        // Get summary info for the job (first row)
        $summary = \DB::selectOne('EXEC sp_job_summary @job = ?, @rssite = ?', [$job, $rssite]);
        return view('irms.irms-layouts.job-job-details', compact('summary'));
    }*/

    public function jobExists(Request $request)
    {
        $job = $request->input('job');
        $exists = \DB::table('rsitemloc')->where('job', $job)->exists();
        return response()->json(['exists' => $exists]);
    }
}
