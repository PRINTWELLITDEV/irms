<?php

namespace App\Http\Controllers;

use App\Models\RsBayLoc;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RsBayLocController extends Controller
{
    /**
     * Display a listing of the bay locations.
     */
    public function index()
    {
        // Call the stored procedure to get bay locations with site logo
        $baylocs = DB::select('EXEC sp_view_bayloc');

        return view('irms.irms-layouts.bay-location', compact('baylocs'));
    }

    /**
     * Store a newly created bay location.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rssite' => 'required|max:8',
            'rsbaynum' => 'required|max:5',
            'createdby' => 'required|max:30',
        ]);

        // Use current datetime for createdate
        $createdate = now();

        // Call the stored procedure to add bay location
        DB::statement('EXEC sp_add_bayloc ?, ?, ?, ?', [
            $validated['rssite'],
            $validated['rsbaynum'],
            $createdate,
            $validated['createdby'],
        ]);

        return redirect()->route('bay-location.index')->with('success', 'Bay location added successfully!');
    }
}
