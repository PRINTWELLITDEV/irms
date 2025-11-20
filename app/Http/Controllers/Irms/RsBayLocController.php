<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\IrmsSite;
use App\Models\RsUser;
use App\Models\RsBayLoc;
use App\Models\RsWhse;


class RsBayLocController extends Controller
{
    public function index()
    {
        if (auth()->user()->level > 3 && auth()->user()->level <= 0) {
            abort(401, 'Unauthorized');
        }
        // $user = auth()->user();
        // $userSite = $user->rssite;
        // $userid = $user->userid;

        // if ($userid === 'sa') {
        //     // Show all bay locations for super admin
        //     $baylocs = \DB::select('EXEC sp_view_baylocs', [null]);
        // } else {
        //     $baylocs = \DB::select('EXEC sp_view_baylocs ?', [$userSite]);
        // }
        
        $sites = IrmsSite::all();
        $warehouses = RsWhse::all();
        // return view('irms.irms-layouts.bay-locations', compact('baylocs', 'sites'));
        return view('irms.irms-layouts.bay-locations', compact('sites', 'warehouses'));

    }

    public function bayList(Request $request)
    {
        $user = auth()->user();
        $userSite = $user->rssite;
        // $userid = $user->userid;

        if (auth()->user()->level == 1) {
            $baylocs = \DB::select('EXEC sp_view_baylocs', [null]);
        } else {
            $baylocs = \DB::select('EXEC sp_view_baylocs ?', [$userSite]);
        }

        // return response()->json($baylocs);
        return view('irms.irms-tables.bay-list', compact('baylocs'))->render();
    }

    /**
     * Store a newly created bay location.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rssite' => 'required|max:8',
            'rswhse' => 'required|max:10',
            'rsbaynum' => 'required|max:5',
        ], [
            'rssite.required' => 'The Site field is required.',
            'rssite.max' => 'The Site field must not exceed 8 characters.',
            'rswhse.required' => 'The Warehouse field is required.',
            'rswhse.max' => 'The Warehouse field must not exceed 10 characters.',
            'rsbaynum.required' => 'The Bay Number field is required.',
            'rsbaynum.max' => 'The Bay Number field must not exceed 5 characters.',
        ]);

        if (RsBayLoc::where('rsbaynum', $validated['rsbaynum'])
                     ->where('rssite', $validated['rssite'])
                     ->where('rswhse', $validated['rswhse'])
                     ->exists()) {
            return response()->json(['message' => "Bay '{$validated['rsbaynum']}' already exists for this warehouse."], 422);
        }

        $bay = strtoupper($validated['rsbaynum']);
        $createdby = auth()->user()->userid ?? 'system';
        $createdate = now();

        try {
            DB::statement('EXEC sp_add_baylocs ?, ?, ?, ?, ?', [
                $validated['rssite'],
                $validated['rswhse'],
                $bay,
                $createdate,
                $createdby,
            ]);
            return response()->json(['message' => "Bay $bay added successfully!"]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
