<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\IrmsSite;
use App\Models\RsUser;
use App\Models\RsBayLoc;


class RsBayLocController extends Controller
{
    public function index()
    {
        if (auth()->user()->level > 3) {
            abort(403, 'Unauthorized');
        }
        $user = auth()->user();
        $userSite = $user->rssite;
        $userid = $user->userid;

        if ($userid === 'sa') {
            // Show all bay locations for super admin
            $baylocs = \DB::select('EXEC sp_view_baylocs', [null]);
        } else {
            $baylocs = \DB::select('EXEC sp_view_baylocs ?', [$userSite]);
        }
        
        $sites = IrmsSite::all();
        return view('irms.irms-layouts.bay-locations', compact('baylocs', 'sites'));
    }

    /**
     * Store a newly created bay location.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rssite' => 'required|max:8',
            'rsbaynum' => 'required|max:5',
        ]);

        if (RsBayLoc::where('rsbaynum', $validated['rsbaynum'])->exists()) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Bay location number already exists.']);
        }

        $createdby = auth()->user()->userid ?? 'system';
        $createdate = now();

        DB::statement('EXEC sp_add_baylocs ?, ?, ?, ?', [
            $validated['rssite'],
            $validated['rsbaynum'],
            $createdate,
            $createdby,
        ]);

        return redirect()->route('baylocs.index')->with('success', 'Bay location added successfully!');
    }
}
