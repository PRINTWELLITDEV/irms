<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

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
        
        // Update to include rswhse in bay locations query
        $baylocs = DB::table('rsbayloc')
            ->select('rsbaynum', 'rssite', 'rswhse')
            ->get();
            
        $site_desc = '';
        if (auth()->user()->level != 1) {
            $user_site = IrmsSite::where('rssite', auth()->user()->rssite)->first();
            $site_desc = $user_site ? $user_site->rssite_desc : '';
        }

        return view('irms.irms-layouts.whse-goodsreceiving', compact('sites', 'warehouses', 'baylocs', 'site_desc'));
    }

    /**
     * Process the goods receiving form submission.
     */
    public function process(Request $request)
    {
        $validated = $request->validate([
            'rssite' => 'required|string|max:8',
            'rswhse' => 'required|string|max:20',
            'date' => 'required|date',
            'jobco' => 'required|string|max:20',
            // 'lot' => 'nullable|string|max:30',
            // 'item' => 'nullable|string|max:100',
            // 'um' => 'nullable|string|max:10',
            // 'pallet_size' => 'required|numeric|min:1',
            'rsbaynum' => 'required|string|max:10',
            'docno' => 'nullable|string|max:30',
        ],[
            'rssite.required' => 'The Site field is required.',
            'rswhse.required' => 'The Warehouse field is required.',
            'date.required' => 'The Date field is required.',
            'jobco.required' => 'The Job field is required.',
            'rsbaynum.required' => 'The Bay Number field is required.',
        ]);

        // Use the helper to check job item details
        $jobItemDetails = $this->fetchJobItemDetails($request->input('rssite'), $request->input('jobco'));
        if (empty($jobItemDetails)) {
            return response()->json(['errors' => ['jobco' => 'Job: ' . strtoupper($request->input('jobco')) . ' does not exist.']], 422);
        }

        return response()->json(['success' => true, 'message' => 'Job: ' . strtoupper($request->input('jobco')) . ' processed successfully!']);
    }

    private function fetchJobItemDetails($rssite, $job)
    {
        $suffix = 0;
        $connections = [
            'PI-SP' => 'pisp_con',
            'FP-SP' => 'fpsp_con',
            'PIGRP-SP' => 'pigrpsp_con',
        ];
        $connection = $connections[$rssite] ?? null;

        if (!$connection || empty($job)) {
            return [];
        }

        return \DB::connection($connection)
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
            ->get()
            ->toArray();
    }

    /**
     * Process the goods received.
     */
    public function processGoodsReceived(Request $request)
    {
        $rows = $request->input('rows', []); // Array of checked rows with all needed fields

        if (!is_array($rows) || count($rows) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'No rows were submitted for processing.'
            ], 422);
        }

        $lockName = 'sp_goodsreceive_process:global';

        $lock = DB::selectOne(
            "DECLARE @res INT;
             EXEC @res = sp_getapplock @Resource = ?, @LockMode = 'Exclusive', @LockOwner = 'Session', @LockTimeout = 0;
             SELECT result = @res;",
            [$lockName]
        );

        if (!$lock || (int) ($lock->result ?? -999) < 0) {
            return response()->json([
                'success' => false,
                'message' => 'Another receiving process is already running. Please wait and try again.'
            ], 409);
        }

        try {
            $seenRows = [];

            // Validate and process each row one-by-one
            foreach ($rows as $index => $row) {
                if (empty($row['rsloc'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Missing rack location for row ' . ($index + 1)
                    ], 400);
                }

                if (empty($row['rssite']) || empty($row['rswhse']) || empty($row['rsbaynum'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Missing required site/warehouse/bay information'
                    ], 400);
                }

                if (empty($row['job']) || empty($row['item'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Missing job or item information'
                    ], 400);
                }

                $rowFingerprint = strtoupper(implode('|', [
                    trim((string) ($row['rssite'] ?? '')),
                    trim((string) ($row['rswhse'] ?? '')),
                    trim((string) ($row['rsbaynum'] ?? '')),
                    trim((string) ($row['rsloc'] ?? '')),
                    trim((string) ($row['rslot'] ?? '')),
                    trim((string) ($row['rspallet_num'] ?? '')),
                    trim((string) ($row['job'] ?? '')),
                    trim((string) ($row['item'] ?? '')),
                    trim((string) ($row['qty'] ?? '')),
                    trim((string) ($row['datercvd'] ?? '')),
                ]));

                if (isset($seenRows[$rowFingerprint])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Duplicate row detected in request at row ' . ($index + 1)
                    ], 422);
                }
                $seenRows[$rowFingerprint] = true;

                DB::beginTransaction();

                try {
                    DB::statement('EXEC sp_goodsreceive_process
                        @rssite = ?, @rswhse = ?, @rsbaynum = ?, @rsloc = ?, @rslot = ?, @rspallet_num = ?, @job = ?, @item = ?, @desc = ?, @um = ?, @qty = ?, @datercvd = ?, @docnum = ?, @createdby = ?',
                        [
                            $row['rssite'],
                            $row['rswhse'],
                            $row['rsbaynum'],
                            $row['rsloc'],
                            $row['rslot'] ?? '',
                            $row['rspallet_num'] ?? '',
                            $row['job'],
                            $row['item'],
                            $row['desc'] ?? '',
                            $row['um'] ?? '',
                            $row['qty'],
                            $row['datercvd'],
                            $row['docnum'] ?? '',
                            auth()->user()->userid
                        ]
                    );

                    DB::commit();
                } catch (Throwable $e) {
                    DB::rollBack();

                    \Log::error('Error processing goods received', [
                        'row' => $row,
                        'error' => $e->getMessage()
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Error processing row ' . ($index + 1) . ': ' . $e->getMessage()
                    ], 500);
                }
            }

            return response()->json(['success' => true, 'message' => 'Goods received successfully!']);
        } finally {
            DB::statement("EXEC sp_releaseapplock @Resource = ?, @LockOwner = 'Session'", [$lockName]);
        }
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
