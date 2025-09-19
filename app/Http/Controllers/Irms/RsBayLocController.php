<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use App\Models\RsBayLoc;
use App\Models\IrmsSite;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RsBayLocController extends Controller
{
    /**
     * Display a listing of the bay locations.
     */
    public function index()
    {
        $baylocs = \DB::select('EXEC sp_view_baylocs');
        $sites = \DB::table('irms_site')->get();
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
