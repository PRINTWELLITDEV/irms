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
        if (auth()->user()->level > 3) {
            abort(401, 'Unauthorized');
        }
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
     * Process the goods received.
     */
    public function processGoodsReceived(Request $request)
    {
        $rows = $request->input('rows'); // Array of checked rows with all needed fields

        foreach ($rows as $row) {
            \DB::statement('EXEC sp_goodsreceive_process 
                @rssite = ?, @rswhse = ?, @rsbaynum = ?, @rsloc = ?, @rslot = ?, @rspallet_num = ?, @job = ?, @item = ?, @desc = ?, @um = ?, @qty = ?, @datercvd = ?, @docnum = ?, @createdby = ?',
                [
                    $row['rssite'],
                    $row['rswhse'],
                    $row['rsbaynum'],
                    $row['rsloc'],
                    $row['rslot'],
                    $row['rspallet_num'],
                    $row['job'],
                    $row['item'],
                    $row['desc'],
                    $row['um'],
                    $row['qty'],
                    $row['datercvd'],
                    $row['docnum'],
                    auth()->user()->userid
                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'Goods received successfully!']);
    }

    /**
     * Get job item details via AJAX.
     */
    public function getJobItemDetails(Request $request)
    {
        // using stored procedure
        // $rssite = $request->input('rssite');
        // $job = $request->input('job');

        // $results = \DB::select('EXEC sp_get_job_item_details @rssite = ?, @job = ?', [
        //     $rssite, $job
        // ]);

        // using query builder

        $rssite = $request->input('rssite');
        $job = $request->input('job');
        $suffix = 0;

        // Map rssite to connection name
        $connections = [
            'PI-SP' => 'pisp_con',
            'FP-SP' => 'fpsp_con',
            'PIGRP-SP' => 'pigrpsp_con',
        ];
        $connection = $connections[$rssite] ?? null;

        if (!$connection) {
            return response()->json(['error' => 'Invalid site'], 400);
        }

        if (empty($job)) {
            return response()->json([]);
        }

        $results = \DB::connection($connection)
            ->table('job as j')
            ->join('item as i', 'i.item', '=', 'j.item')
            ->select(
                'j.job',
                'j.suffix',
                'j.item',
                'i.description',
                'i.Uf_itemdesc_ext',
                'i.u_m',
                'i.Uf_Item_PalletSize'
            )
            ->where('j.job', $job)
            ->where('j.suffix', $suffix)
            ->get();

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
        $item = $request->input('item');
        $pallet_size = $request->input('pallet_size');

        $results = \DB::select('EXEC sp_get_rsloc_list @rssite = ?, @rswhse = ?, @rsbaynum = ?, @item = ?, @pallet_size = ?', [
            $rssite, $rswhse, $rsbaynum, $item, $pallet_size
        ]);

        return response()->json($results);
    }
}
