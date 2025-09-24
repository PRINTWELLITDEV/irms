<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\IrmsSite;
use App\Models\RsUser;
use App\Models\Rswhse;
use App\Models\RsBayLoc;
use App\Models\RsGoodsDispatching;

class RsGoodsDispatchingController extends Controller
{
    /**
     * Show the goods dispatching form.
     */
    public function index()
    {
        $sites = IrmsSite::all();
        $warehouses = Rswhse::all();
        $baylocs = RsBayLoc::all();
        return view('irms.irms-layouts.whse-goodsdispatching', compact('sites', 'warehouses', 'baylocs'));
    }

    /**
     * Process the goods dispatching form submission.
     */
    public function process(Request $request)
    {
        $validated = $request->validate([
            'rssite' => 'required|string|max:8',
            'whse' => 'required|string|max:20',
            'date' => 'required|date',
            'jobco' => 'required|string|max:20',
            'lot' => 'nullable|string|max:30',
            'item' => 'nullable|string|max:100',
            'um' => 'nullable|string|max:10',
            'pallet_size' => 'required|numeric|min:1',
            'baynum' => 'required|string|max:10',
            'docno' => 'nullable|string|max:30',
        ]);

        // Save to database or call a stored procedure here as needed
        // Example:
        // \DB::table('goods_dispatching')->insert($validated);

        return redirect()->back()->with('success', 'Goods dispatching processed successfully!');
    }
}
