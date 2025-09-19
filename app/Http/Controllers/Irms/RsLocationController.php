<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RsLocation;
use App\Models\IrmsSite; 

class RsLocationController extends Controller
{
    public function index()
    {
        // Fetch rack locations using the stored procedure
        $racklocs = DB::select('EXEC sp_view_rslocs');
        $sites = IrmsSite::all();
        $warehouses = DB::table('rswhse')->get();
        $baynums = DB::table('rsbayloc')->get();
        // Pass to the view
        return view('irms.irms-layouts.rack-locations', compact('racklocs', 'sites', 'warehouses', 'baynums'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rssite' => 'required|max:8',
            'rswhse' => 'required|max:10',
            'rsbaynum' => 'required|max:5',
            'rsloc' => 'required|max:15',
            'rsdec' => 'nullable|max:13',
            'qty' => 'required|numeric',
        ]);

        $createdate = now();
        $createdby = auth()->user()->userid ?? 'system';

        \DB::statement('EXEC sp_add_rslocs ?, ?, ?, ?, ?, ?, ?, ?', [
            $validated['rssite'],
            $validated['rswhse'],
            $validated['rsbaynum'],
            $validated['rsloc'],
            $validated['rsdec'],
            $validated['qty'],
            $createdate,
            $createdby
        ]);

        return redirect()->route('racklocations.index')->with('success', 'Rack location added successfully!');
    }
}
