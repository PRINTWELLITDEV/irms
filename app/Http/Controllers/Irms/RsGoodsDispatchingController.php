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
        if (auth()->user()->level > 3) {
            abort(403, 'Unauthorized');
        }
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

    /**
     * Get job item details for dispatching.
     */
    public function getJobItemDetails(Request $request)
    {
        $rssite = $request->input('rssite');
        $job = $request->input('jobco');

        $result = \DB::select('EXEC sp_dispatching_job_item_details @rssite = ?, @job = ?', [
            $rssite, $job
        ]);

        return response()->json($result ? $result[0] : []);
    }

    /**
     * Get item list in RS location.
     */
    public function getItemInRsLocList(Request $request)
    {
        $rssite = $request->input('rssite');
        $job = $request->input('job');

        $results = \DB::select('EXEC sp_get_item_in_rsloc_list @rssite = ?, @job = ?', [
            $rssite, $job
        ]);

        return response()->json($results);
    }

    public function processGoodsDispatch(Request $request)
    {
        $rows = $request->input('rows'); // Array of checked rows with all needed fields

        foreach ($rows as $row) {
            \DB::statement('EXEC sp_goodsdispatch_process 
                @rssite = ?, @rswhse = ?, @rsloc = ?, @rslot = ?, @rspallet_num = ?, @job = ?, @item = ?, @desc = ?, @um = ?, @qty = ?, @datedispatch = ?, @docnum = ?, @createdby = ?',
                [
                    $row['rssite'],
                    $row['rswhse'],
                    $row['rsloc'],
                    $row['rslot'],
                    $row['rspallet_num'],
                    $row['job'],
                    $row['item'],
                    $row['desc'],
                    $row['um'],
                    $row['qty'],
                    $row['datedispatch'],
                    $row['docno'],
                    auth()->user()->userid
                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'Goods dispatched successfully!']);
    }
}
