<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\IrmsSite;
use App\Models\RsUser;
use App\Models\Rswhse;
use App\Models\RsBayLoc;
use App\Models\RsGoodsReceiving;

class RsGoodsReceivingController extends Controller
{
    /**
     * Show the goods receiving form.
     */
    public function index()
    {
        $sites = IrmsSite::all();
        $warehouses = Rswhse::all();
        $baylocs = RsBayLoc::all();
        return view('irms.irms-layouts.whse-goodsreceiving', compact('sites', 'warehouses', 'baylocs'));
    }

    /**
     * Process the goods receiving form submission.
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

        

        return redirect()->back()->with('success', 'Goods receiving processed successfully!');
    }

    /**
     * Get job item details via AJAX.
     */
    public function getJobItemDetails(Request $request)
    {
        $rssite = $request->input('rssite');
        $job = $request->input('job');

        $results = \DB::select('EXEC sp_get_job_item_details @rssite = ?, @job = ?', [
            $rssite, $job
        ]);

        return response()->json($results);
    }

    /**
     * Get RS location list via AJAX.
     */
    public function getRsLocList(Request $request)
    {
        $rssite = $request->input('rssite');
        $rswhse = $request->input('rswhse');
        $rsbaynum = $request->input('rsbaynum');

        $results = \DB::select('EXEC sp_get_rsloc_list @rssite = ?, @rswhse = ?, @rsbaynum = ?', [
            $rssite, $rswhse, $rsbaynum
        ]);

        return response()->json($results);
    }
}
