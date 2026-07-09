<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Throwable;

use App\Models\IrmsSite;
use App\Models\Rswhse;
use App\Models\RsBayLoc;
// Ensure your remaining location models are imported here:
// use App\Models\RsRackLoc; 

class RsQuantityMoveController extends Controller
{
    /**
     * Display the initial form view.
     */
    public function index()
    {
        $userSite = auth()->user()->rssite;
        $sites = IrmsSite::all();
        
        // Load only the initial master list of warehouses for the current user site
        $warehouses = Rswhse::where('rssite', $userSite)->get();
        
        // Pass empty arrays for dependent selections so the views don't break on initialization
        $bays = [];
        $rslocs = [];
        
        return view('irms.irms-layouts.quantity-move', compact('sites', 'warehouses', 'bays', 'rslocs'));
    }

    /**
     * AJAX Endpoint: Get Bays filtered by selected Warehouse and Site
     */
    public function getBays(Request $request)
    {
        $site = $request->get('site') ?? auth()->user()->rssite;
        $warehouse = $request->get('warehouse');

        if (!$warehouse) {
            return response()->json([]);
        }

        $bays = RsBayLoc::where('rssite', $site)
                        ->where('rswhse', $warehouse)
                        ->select('rsbaynum')
                        ->distinct()
                        ->get();

        return response()->json($bays);
    }

    /**
     * AJAX Endpoint: Get Rack Locations filtered by selected Bay, Warehouse, and Site
     */
    public function getRackLocations(Request $request)
    {
        $site = $request->get('site') ?? auth()->user()->rssite;
        $warehouse = $request->get('warehouse');
        $bay = $request->get('bay');

        if (!$warehouse || !$bay) {
            return response()->json([]);
        }

        // Adjust the query below to match your exact DB structure for rack locations
        $rackLocations = DB::table('rslocation') // Replace with your actual table or model
                            ->where('rssite', $site)
                            ->where('rswhse', $warehouse)
                            ->where('rsbaynum', $bay)
                            ->select('rsloc')
                            ->distinct()
                            ->get();

        return response()->json($rackLocations);
    }

    /**
     * AJAX Endpoint: Get Positions / Grid details filtered by Rack Location
     */
    public function getPositions(Request $request)
    {
        $site = $request->get('site') ?? auth()->user()->rssite;
        $warehouse = $request->get('warehouse');
        $rackLocation = $request->get('rack_location');

        if (!$rackLocation) {
            return response()->json([]);
        }

        // Adjust query to target your position field setup
        $positions = DB::table('rs_locs')
                        ->where('rssite', $site)
                        ->where('rswhse', $warehouse)
                        ->where('rsloc', $rackLocation)
                        ->select('rsposition') // Replace with your exact column name
                        ->distinct()
                        ->get();

        return response()->json($positions);
    }

    public function create() { }

    public function store(Request $request) { }

    public function show(string $id) { }

    public function edit(string $id) { }

    public function update(Request $request, string $id) { }

    public function destroy(string $id) { }
}