<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable; // add this

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
            abort(401, 'Unauthorized');
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
            // 'rswhse' => 'required|string|max:20',
            'date' => 'required|date',
            'jobco' => 'required|string|max:20',
            // 'lot' => 'nullable|string|max:30',
            // 'item' => 'nullable|string|max:100',
            // 'um' => 'nullable|string|max:10',
            // 'pallet_size' => 'required|numeric|min:1',
            // 'rsbaynum' => 'required|string|max:10',
            'docno' => 'nullable|string|max:30',
        ],[
            'rssite.required' => 'The Site field is required.',
            // 'rswhse.required' => 'The Warehouse field is required.',
            'date.required' => 'The Date field is required.',
            'jobco.required' => 'The Job field is required.',
            // 'rsbaynum.required' => 'The Bay Number field is required.',
        ]);

        $jobItemDetails = $this->fetchJobItemDetails($validated['rssite'], $validated['jobco']);
        if (empty($jobItemDetails)) {
            return response()->json(['errors' => ['jobco' => 'Job: ' . strtoupper($request->input('jobco')) . ' does not exist.']], 422);
        }
        return response()->json(['success' => true, 'message' => 'Job: ' . strtoupper($validated['jobco']) . ' processed successfully!']);
        // return redirect()->back()->with('success', 'Job: ' . strtoupper($validated['jobco']) . ' processed successfully!');
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

    private function fetchJobItemDetails($rssite, $job){
        return \DB::select('EXEC sp_dispatching_job_item_details @rssite = ?, @job = ?', [
            $rssite, $job
        ]);
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
        $rows = $request->input('rows', []);

        if (!is_array($rows) || count($rows) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'No rows were submitted for processing.'
            ], 422);
        }

        $lockName = 'sp_goodsdispatch_process:global';

        $lock = DB::selectOne(
            "DECLARE @res INT;
             EXEC @res = sp_getapplock @Resource = ?, @LockMode = 'Exclusive', @LockOwner = 'Session', @LockTimeout = 0;
             SELECT result = @res;",
            [$lockName]
        );

        if (!$lock || (int)($lock->result ?? -999) < 0) {
            return response()->json([
                'success' => false,
                'message' => 'Another dispatching process is already running. Please wait and try again.'
            ], 409);
        }

        try {
            $seenRows = [];

            foreach ($rows as $index => $row) {
                if (
                    empty($row['rssite']) || empty($row['rsloc']) || empty($row['job']) ||
                    empty($row['item']) || !isset($row['qty']) || empty($row['datedispatch'])
                ) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Missing required data at row ' . ($index + 1)
                    ], 400);
                }

                $qty = (float)$row['qty'];
                if ($qty <= 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid quantity at row ' . ($index + 1)
                    ], 422);
                }

                $rowFingerprint = strtoupper(implode('|', [
                    trim((string)($row['rssite'] ?? '')),
                    trim((string)($row['rswhse'] ?? '')),
                    trim((string)($row['rsloc'] ?? '')),
                    trim((string)($row['rslot'] ?? '')),
                    trim((string)($row['rspallet_num'] ?? '')),
                    trim((string)($row['job'] ?? '')),
                    trim((string)($row['item'] ?? '')),
                    trim((string)($row['qty'] ?? '')),
                    trim((string)($row['datedispatch'] ?? '')),
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
                    DB::statement(
                        'EXEC sp_goodsdispatch_process
                        @rssite = ?, @rswhse = ?, @rsloc = ?, @rslot = ?, @rspallet_num = ?, @job = ?, @item = ?, @desc = ?, @um = ?, @qty = ?, @datedispatch = ?, @docnum = ?, @createdby = ?',
                        [
                            strtoupper(trim((string)($row['rssite'] ?? ''))),
                            strtoupper(trim((string)($row['rswhse'] ?? ''))),
                            strtoupper(trim((string)($row['rsloc'] ?? ''))),
                            strtoupper(trim((string)($row['rslot'] ?? ''))),
                            strtoupper(trim((string)($row['rspallet_num'] ?? ''))),
                            strtoupper(trim((string)($row['job'] ?? ''))),
                            strtoupper(trim((string)($row['item'] ?? ''))),
                            strtoupper(trim((string)($row['desc'] ?? ''))),
                            strtoupper(trim((string)($row['um'] ?? ''))),
                            $qty,
                            $row['datedispatch'],
                            strtoupper(trim((string)($row['docno'] ?? ''))),
                            auth()->user()->userid
                        ]
                    );

                    DB::commit();
                } catch (Throwable $e) {
                    DB::rollBack();

                    \Log::error('Error processing goods dispatch row', [
                        'row' => $index + 1,
                        'error' => $e->getMessage(),
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Error processing row ' . ($index + 1) . ': ' . $e->getMessage()
                    ], 500);
                }
            }

            return response()->json(['success' => true, 'message' => 'Goods dispatched successfully!']);
        } finally {
            DB::statement("EXEC sp_releaseapplock @Resource = ?, @LockOwner = 'Session'", [$lockName]);
        }
    }
}
