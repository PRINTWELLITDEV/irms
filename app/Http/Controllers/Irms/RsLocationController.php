<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\IrmsSite;
use App\Models\RsUser;
use App\Models\RsLocation;


class RsLocationController extends Controller
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
        //     // Show all warehouses for super admin
        //     $racklocs = \DB::select('EXEC sp_view_rslocs', [null]);
        // } else {
        //     $racklocs = \DB::select('EXEC sp_view_rslocs ?', [$userSite]);
        // }

        $sites = IrmsSite::all();
        $warehouses = DB::table('rswhse')->get();
        $baynums = DB::table('rsbayloc')->get();
        // return view('irms.irms-layouts.rack-locations', compact('racklocs', 'sites', 'warehouses', 'baynums'));
        return view('irms.irms-layouts.rack-locations', compact('sites', 'warehouses', 'baynums'));

    }

    public function rackList(Request $request)
    {
        $user = auth()->user();
        $userSite = $user->rssite;
        // $userid = $user->userid;

        if (auth()->user()->level == 1) {
            $racklocs = \DB::select('EXEC sp_view_rslocs', [null]);
        } else {
            $racklocs = \DB::select('EXEC sp_view_rslocs ?', [$userSite]);
        }

        return response()->json($racklocs);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rssite' => 'required|max:8',
            'rswhse' => 'required|max:10',
            'rsbaynum' => 'required|max:5',
            'rsloc' => 'required|max:15',
            'rsdesc' => 'nullable|max:13',
        ], [
            'rssite.required' => 'The Site field is required.',
            'rssite.max' => 'The Site field must not exceed 8 characters.',
            'rswhse.required' => 'The Warehouse field is required.',
            'rswhse.max' => 'The Warehouse field must not exceed 10 characters.',
            'rsbaynum.required' => 'The Bay Number field is required.',
            'rsbaynum.max' => 'The Bay Number field must not exceed 5 characters.',
            'rsloc.required' => 'The Rack Location field is required.',
            'rsloc.max' => 'The Rack Location field must not exceed 15 characters.',
            'rsdesc.max' => 'The Description field must not exceed 13 characters.',
        ]);

        if (RsLocation::where('rsloc', $validated['rsloc'])
                      ->where('rssite', $validated['rssite'])
                      ->exists()) {
            return response()->json(['message' => 'Rack location already exists for this site.'], 422);
        }

        $rack = $validated['rsloc'];
        $createdate = now();
        $createdby = auth()->user()->userid ?? 'system';

        try {
            \DB::statement('EXEC sp_add_rslocs ?, ?, ?, ?, ?, ?, ?, ?', [
                $validated['rssite'],
                $validated['rswhse'],
                $validated['rsbaynum'],
                $validated['rsloc'],
                $validated['rsdesc'],
                0, // Set quantity to 0
                $createdate,
                $createdby
            ]);
            return response()->json(['message' => "Rack $rack added successfully!"]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function rackMapGrid(Request $request)
    {
        $rssite = $request->input('rssite');
        $rswhse = $request->input('rswhse');
        $rsbaynum = $request->input('rsbaynum');

        $locations = \DB::select('EXEC sp_rack_map ?, ?, ?', [$rssite, $rswhse, $rsbaynum]);

        // Return as JSON for AJAX
        return response()->json($locations);
    }
}
