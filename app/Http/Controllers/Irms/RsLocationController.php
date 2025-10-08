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
        // Fetch rack locations using the stored procedure
        // $racklocs = DB::select('EXEC sp_view_rslocs');
        $user = auth()->user();
        $userSite = $user->rssite;
        $userid = $user->userid;

        if ($userid === 'sa') {
            // Show all warehouses for super admin
            $racklocs = \DB::select('EXEC sp_view_rslocs', [null]);
        } else {
            $racklocs = \DB::select('EXEC sp_view_rslocs ?', [$userSite]);
        }

        $sites = IrmsSite::all();
        $warehouses = DB::table('rswhse')->get();
        $baynums = DB::table('rsbayloc')->get();
        return view('irms.irms-layouts.rack-locations', compact('racklocs', 'sites', 'warehouses', 'baynums'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rssite' => 'required|max:8',
            'rswhse' => 'required|max:10',
            'rsbaynum' => 'required|max:5',
            'rsloc' => 'required|max:15',
            'rsdesc' => 'nullable|max:13',
        ]);
        if (RsLocation::where('rsloc', $validated['rsloc'])->exists()) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Rack location code already exists.']);
        }

        $createdate = now();
        $createdby = auth()->user()->userid ?? 'system';

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

        return redirect()->route('racklocations.index')->with('success', 'Rack location added successfully!');
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
