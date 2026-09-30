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

class RsGoodsRelocatingController extends Controller
{

    public function index()
    {
        //only allow users with quantity_move permission to access this page
        //Author: Jim Dominic Pabalate
        //Date Created: September 17, 2026
        if (!auth()->user()->hasQuantityMoveAccess()) {
            abort(403, 'Unauthorized');
        }

        $sites = IrmsSite::all();
        $warehouses = Rswhse::all();
        
        // Update to include rswhse in bay locations query
        $baylocs = DB::table('rsbayloc')
            ->select('rsbaynum', 'rssite', 'rswhse')
            ->get();

        $itemlocs = DB::table('rsitemloc')
            ->select('rsloc',
                    'rssite',
                    'rswhse',
                    'rsbaynum')
            ->get();
            
        $site_desc = '';
        if (auth()->user()->level != 1) {
            $user_site = IrmsSite::where('rssite', auth()->user()->rssite)->first();
            $site_desc = $user_site ? $user_site->rssite_desc : '';
        }

        return view('irms.irms-layouts.whse-goodsrelocating', compact('sites', 'warehouses', 'baylocs', 'itemlocs', 'site_desc'));
    }

    public function getPalletSuggestions(Request $request)
    {
        $pallets = DB::table('rsitemloc')
            ->where('rssite', $request->rssite)
            ->where('rswhse', $request->rswhse)
            ->where('rsbaynum', $request->rsbaynum)
            ->where('rsloc', $request->rsloc)
            ->whereNotNull('rspallet_num')
            ->where('rspallet_num', '!=', '')
            ->select('rspallet_num')
            ->distinct()
            ->orderBy('rspallet_num')
            ->get();

        return response()->json($pallets);
    }


    public function moveItem(Request $request)
    {
        $request->validate([
            'from_rssite'   => 'required',
            'from_rswhse'   => 'required',
            'from_rsbaynum' => 'required',
            'from_rsloc'    => 'required',
            'from_rspallet' => 'nullable|string',   
            'from_jobco'    => 'required',

            'to_rssite'     => 'required',
            'to_rswhse'     => 'required',
            'to_rsbaynum'   => 'required',
            'to_rsloc'      => 'required',
            'to_rspallet'   => 'nullable|max:10|alpha_num',

            'qty_to_move'   => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();

        try {

            // FIND SOURCE ITEM

        $sourceQuery = DB::table('rsitemloc')
            ->where('rssite', $request->from_rssite)
            ->where('rswhse', $request->from_rswhse)
            ->where('rsbaynum', $request->from_rsbaynum)
            ->where('rsloc', $request->from_rsloc)
            ->where('job', $request->from_jobco);

        if ($request->filled('from_rspallet')) {
            $sourceQuery->where('rspallet_num', $request->from_rspallet);
        } else {
            $sourceQuery->where(function ($query) {
                $query->whereNull('rspallet_num')
                    ->orWhere('rspallet_num', '');
            });
        }

        $source = $sourceQuery
            ->lockForUpdate()
            ->first();

            if (!$source) {
                throw new \Exception('Source item was not found.');
            }


            // CHECK RESTRICTED PALLET NUMBERS WIP to 5 is not allowed
            $restrictedPallets = [
                'WIP',
                'STICKERING',
                'JIT',
                'FOR STICKE',
                'Stickering'
            ];

        $fromPallet = strtoupper(trim((string) $request->from_rspallet));
        $toPallet   = strtoupper(trim((string) $request->to_rspallet));

        // Get destination location quantity
        $destinationQty = (float) DB::table('rslocation')
            ->where('rssite', $request->to_rssite)
            ->where('rswhse', $request->to_rswhse)
            ->where('rsbaynum', $request->to_rsbaynum)
            ->where('rsloc', $request->to_rsloc)
            ->value('qty');

        // Get destination pallet
        $destinationPallet = DB::table('rsitemloc')
            ->where('rssite', $request->to_rssite)
            ->where('rswhse', $request->to_rswhse)
            ->where('rsbaynum', $request->to_rsbaynum)
            ->where('rsloc', $request->to_rsloc)
            ->value('rspallet_num');

        $destinationPallet = strtoupper(trim((string) $destinationPallet));

        // Check if destination is empty
        $destinationIsEmpty = $destinationQty <= 0;

        if (!$destinationIsEmpty) {

            // Source is restricted and destination is different
            if (
                in_array($fromPallet, $restrictedPallets, true) &&
                $fromPallet !== $destinationPallet
            ) {
                throw new \Exception(
                    "Pallet {$fromPallet} cannot be moved to pallet {$destinationPallet}."
                );
            }

            // Destination is restricted and source is different
            if (
                in_array($destinationPallet, $restrictedPallets, true) &&
                $fromPallet !== $destinationPallet
            ) {
                throw new \Exception(
                    "Pallet {$fromPallet} cannot be moved to pallet {$destinationPallet}."
                );
            }
        }






            // CHECK QUANTITY

            $qtyToMove = (float) $request->qty_to_move;
            $sourceQty = (float) $source->qty;

            if ($qtyToMove > $sourceQty) {
                throw new \Exception(
                    'Quantity to move cannot be greater than the available quantity.'
                );
            }
            // CHECK DESTINATION LOCATION CAPACITY

            $destinationQty = (float) DB::table('rslocation')
                ->where('rssite', $request->to_rssite)
                ->where('rswhse', $request->to_rswhse)
                ->where('rsbaynum', $request->to_rsbaynum)
                ->where('rsloc', $request->to_rsloc)
                ->value('qty');

            // Get the same pallet-size limit used by rackMapGrid()
            $connections = [
                'PI-SP'    => 'pisp_con',
                'FP-SP'    => 'fpsp_con',
                'PIGRP-SP' => 'pigrpsp_con',
            ];

            $connection = $connections[$request->from_rssite] ?? null;

            $destinationLimit = 0;

            if ($connection) {

                $jobDetail = DB::connection($connection)
                    ->table('job as j')
                    ->join('item as i', 'i.item', '=', 'j.item')
                    ->select('i.Uf_Item_PalletSize')
                    ->where('j.job', $source->job)
                    ->where('j.suffix', 0)
                    ->where('j.item', $source->item)
                    ->first();

                if ($jobDetail) {
                    $destinationLimit = (float) ($jobDetail->Uf_Item_PalletSize ?? 0);
                }
            }

            // Check if destination will exceed its maximum capacity
            $destinationTotal = $destinationQty + $qtyToMove;

            if ($destinationLimit > 0 && $destinationTotal > $destinationLimit) {
                throw new \Exception(
                    'The destination location cannot hold this quantity.'
                );
            }



            // UPDATE SOURCE

            $remainingQty = $sourceQty - $qtyToMove;

            if ($remainingQty <= 0) {

                // kapag yung buong quantity was moved
                $deleteQuery = DB::table('rsitemloc')
                    ->where('rssite', $request->from_rssite)
                    ->where('rswhse', $request->from_rswhse)
                    ->where('rsbaynum', $request->from_rsbaynum)
                    ->where('rsloc', $request->from_rsloc)
                    ->where('job', $request->from_jobco);

                if ($request->filled('from_rspallet')) {
                    $deleteQuery->where('rspallet_num', $request->from_rspallet);
                } else {
                    $deleteQuery->where(function ($query) {
                        $query->whereNull('rspallet_num')
                            ->orWhere('rspallet_num', '');
                    });
                }

                $deleteQuery->delete();

            } else {

                // kapag hindi yung whole item ang namoved
                $updateQuery = DB::table('rsitemloc')
                    ->where('rssite', $request->from_rssite)
                    ->where('rswhse', $request->from_rswhse)
                    ->where('rsbaynum', $request->from_rsbaynum)
                    ->where('rsloc', $request->from_rsloc)
                    ->where('job', $request->from_jobco);

                if ($request->filled('from_rspallet')) {
                    $updateQuery->where('rspallet_num', $request->from_rspallet);
                } else {
                    $updateQuery->where(function ($query) {
                        $query->whereNull('rspallet_num')
                            ->orWhere('rspallet_num', '');
                    });
                }

                $updateQuery->update([
                    'qty' => $remainingQty
                ]);
            }


                // GENERATE TWO NEW TRANSACTION NUMBERS

                $transYear = date('y');

                $lastTran = DB::table('rslasttran')
                    ->where('trans_year', $transYear)
                    ->lockForUpdate()
                    ->first();

                if ($lastTran) {

                    $fromLastNum = $lastTran->last_num + 1;
                    $toLastNum = $fromLastNum + 1;

                    // Update last number to the second transaction
                    DB::table('rslasttran')
                        ->where('trans_year', $transYear)
                        ->update([
                            'last_num' => $toLastNum
                        ]);

                } else {

                    $fromLastNum = 1;
                    $toLastNum   = 2;

                    DB::table('rslasttran')->insert([
                        'trans_year' => $transYear,
                        'last_num'   => $toLastNum
                    ]);
                }

                $fromTransNum = $transYear . '-' . str_pad(
                    $fromLastNum,
                    7,
                    '0',
                    STR_PAD_LEFT
                );

                $toTransNum = $transYear . '-' . str_pad(
                    $toLastNum,
                    7,
                    '0',
                    STR_PAD_LEFT
                );


                // INSERT FROM TRANSACTION

                DB::table('rstrans')->insert([
                    'rssite'       => $request->from_rssite,
                    'trans_num'    => $fromTransNum,
                    'trxdate'      => now(),
                    'trxtype'      => 'M',

                    'item'         => $source->item,
                    'desc'         => $source->desc,
                    'job'          => $source->job,

                    'rswhse'       => $request->from_rswhse,
                    'rsloc'        => $request->from_rsloc,
                    'rslot'        => $source->job . '-1',
                    'rspallet_num' => $request->filled('from_rspallet')
                        ? $request->from_rspallet
                        : null,
                    // NEGATIVE because goods are leaving FROM
                    'qty'          => $qtyToMove * -1,

                    'um'           => $source->um,
                    'docnum'       => $request->docno ?? '',
                    'createdby'    => auth()->user()->userid,
                    'createdate'   => now(),
                ]);


                // INSERT TO TRANSACTION

                DB::table('rstrans')->insert([
                    'rssite'       => $request->to_rssite,
                    'trans_num'    => $toTransNum,
                    'trxdate'      => now(),
                    'trxtype'      => 'M',

                    'item'         => $source->item,
                    'desc'         => $source->desc,
                    'job'          => $source->job,

                    'rswhse'       => $request->to_rswhse,
                    'rsloc'        => $request->to_rsloc,
                    'rslot'        => $source->job . '-1',
                    'rspallet_num' => $request->to_rspallet,

                    // POSITIVE because goods are entering to new destination
                    'qty'          => $qtyToMove,

                    'um'           => $source->um,
                    'docnum'       => $request->docno ?? '',
                    'createdby'    => auth()->user()->userid,
                    'createdate'   => now(),
                ]);

      


                    // INSERT DESTINATION ITEM
                    // CHECK EXISTING DESTINATION ITEM

                $destinationItem = DB::table('rsitemloc')
                    ->where('rssite', $request->to_rssite)
                    ->where('rswhse', $request->to_rswhse)
                    ->where('rsbaynum', $request->to_rsbaynum)
                    ->where('rsloc', $request->to_rsloc)
                    ->where('item', $source->item)
                    ->where('job', $source->job)
                    ->where(function ($query) use ($request) {

                        if ($request->filled('to_rspallet')) {
                            $query->where('rspallet_num', $request->to_rspallet);
                        } else {
                            $query->whereNull('rspallet_num')
                                ->orWhere('rspallet_num', '');
                        }

                    })
                    ->lockForUpdate()
                    ->first();


            // SAME ITEM ALREADY EXISTS → MERGE

            if ($destinationItem) {

                DB::table('rsitemloc')
                    ->where('rssite', $request->to_rssite)
                    ->where('rswhse', $request->to_rswhse)
                    ->where('rsbaynum', $request->to_rsbaynum)
                    ->where('rsloc', $request->to_rsloc)
                    ->where('item', $source->item)
                    ->where('job', $source->job)
                    ->where(function ($query) use ($request) {

                        if ($request->filled('to_rspallet')) {
                            $query->where('rspallet_num', $request->to_rspallet);
                        } else {
                            $query->whereNull('rspallet_num')
                                ->orWhere('rspallet_num', '');
                        }

                    })
                    ->update([
                        'qty' => DB::raw('ISNULL(qty, 0) + ' . $qtyToMove),
                    ]);



            } else {

                $differentItem = DB::table('rsitemloc')
                    ->where('rssite', $request->to_rssite)
                    ->where('rswhse', $request->to_rswhse)
                    ->where('rsbaynum', $request->to_rsbaynum)
                    ->where('rsloc', $request->to_rsloc)
                    ->where('qty', '>', 0)
                    ->exists();

                if ($differentItem) {
                    throw new \Exception(
                        'The selected destination location has a different pallet.');
                }

                // CREATE NEW DESTINATION RECORD

                DB::table('rsitemloc')->insert([
                    'rssite'       => $request->to_rssite,
                    'rswhse'       => $request->to_rswhse,
                    'rsbaynum'     => $request->to_rsbaynum,
                    'rsloc'        => $request->to_rsloc,
                    'rspallet_num' => $request->to_rspallet,
                    'job'          => $source->job,
                    'item'         => $source->item,
                    'desc'         => $source->desc,
                    'qty'          => $qtyToMove,
                    'um'           => $source->um,
                    'createdby'    => auth()->user()->userid,
                    'createdate'   => now(),
                ]);
            }

            // GET SOURCE LOCATION QUARANTINE STATUS
            $sourceLocation = DB::table('rslocation')
                ->where('rssite', $request->from_rssite)
                ->where('rswhse', $request->from_rswhse)
                ->where('rsbaynum', $request->from_rsbaynum)
                ->where('rsloc', $request->from_rsloc)
                ->lockForUpdate()
                ->first();

            $destinationLocation = DB::table('rslocation')
                ->where('rssite', $request->to_rssite)
                ->where('rswhse', $request->to_rswhse)
                ->where('rsbaynum', $request->to_rsbaynum)
                ->where('rsloc', $request->to_rsloc)
                ->first();

            if (!$sourceLocation || !$destinationLocation) {
                throw new \Exception('Source or destination location was not found.');
            }

            $sourceIsQuarantine = (bool) $sourceLocation->isQuarantine;
            $destinationIsQuarantine = (bool) $destinationLocation->isQuarantine;

            // DO NOT ALLOW QUARANTINE ↔ NON-QUARANTINE
            if ($sourceIsQuarantine !== $destinationIsQuarantine) {
                throw new \Exception(
                    'Items cannot be moved between Quarantine and regular locations.'
                );
            }
                // UPDATE LOCATION QTY

                DB::table('rslocation')
                    ->where('rssite', $request->from_rssite)
                    ->where('rswhse', $request->from_rswhse)
                    ->where('rsbaynum', $request->from_rsbaynum)
                    ->where('rsloc', $request->from_rsloc)
                    ->decrement('qty', $qtyToMove);


                DB::table('rslocation')
                    ->where('rssite', $request->to_rssite)
                    ->where('rswhse', $request->to_rswhse)
                    ->where('rsbaynum', $request->to_rsbaynum)
                    ->where('rsloc', $request->to_rsloc)
                    ->update([
                        'qty' => DB::raw('ISNULL(qty, 0) + ' . $qtyToMove),

                        // Transfer quarantine status
                        'isQuarantine' => $sourceIsQuarantine
                    ]);

                    
                // ONLY REMOVE QUARANTINE FROM SOURCE
                // IF THE ENTIRE QUANTITY WAS MOVED
                if ($remainingQty <= 0) {

                    DB::table('rslocation')
                        ->where('rssite', $request->from_rssite)
                        ->where('rswhse', $request->from_rswhse)
                        ->where('rsbaynum', $request->from_rsbaynum)
                        ->where('rsloc', $request->from_rsloc)
                        ->update([
                            'isQuarantine' => false
                        ]);
                }
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Item successfully moved.'
                ]);

        } catch (Throwable $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Get Bays
    public function getBays(Request $request)
    {
        $bays = DB::table('rsbayloc')
            ->select('rsbaynum')
            // ->where('rssite', $request->rssite)
            ->where('rswhse', $request->rswhse)
            ->distinct()
            ->orderBy('rsbaynum')
            ->get();

        return response()->json($bays);
    }

    // public function getLocations(Request $request)
    // {
    //     $query = DB::table('rslocation')
    //         ->select(
    //             'rsloc',
    //             'qty'
    //         )
    //         ->where('rssite', $request->rssite)
    //         ->where('rswhse', $request->rswhse)
    //         ->where('rsbaynum', $request->rsbaynum)
    //         ->distinct();

    //     // FROM LOCATION
    //     // Only show locations that currently contain inventory
    //     if ($request->occupied_only == 1) {

    //         $query->whereExists(function ($subquery) {

    //             $subquery->select(DB::raw(1))
    //                 ->from('rsitemloc')
    //                 ->whereColumn('rsitemloc.rssite', 'rslocation.rssite')
    //                 ->whereColumn('rsitemloc.rswhse', 'rslocation.rswhse')
    //                 ->whereColumn('rsitemloc.rsbaynum', 'rslocation.rsbaynum')
    //                 ->whereColumn('rsitemloc.rsloc', 'rslocation.rsloc')
    //                 ->where('rsitemloc.qty', '>', 0);

    //         });
    //     }

    //     return response()->json(
    //         $query->orderBy('rsloc')->get()
    //     );
    // }
    public function getLocations(Request $request)
    {
        $query = DB::table('rslocation')
            ->select(
                'rsloc',
                'qty'
            )
            ->where('rssite', $request->rssite)
            ->where('rswhse', $request->rswhse)
            ->where('rsbaynum', $request->rsbaynum)
            ->distinct();

        // FROM LOCATION========
        // Only show locations that contain inventory
        if ($request->occupied_only == 1) {

            $query->whereExists(function ($subquery) {

                $subquery->select(DB::raw(1))
                    ->from('rsitemloc')
                    ->whereColumn('rsitemloc.rssite', 'rslocation.rssite')
                    ->whereColumn('rsitemloc.rswhse', 'rslocation.rswhse')
                    ->whereColumn('rsitemloc.rsbaynum', 'rslocation.rsbaynum')
                    ->whereColumn('rsitemloc.rsloc', 'rslocation.rsloc')
                    ->where('rsitemloc.qty', '>', 0);

            });
        }

        // TO LOCATION=================
        // Only show empty locations locations containing the SAME item
        if ($request->to_location == 1 && $request->item) {

            $item = $request->item;

            // Exclude current FROM location
            if ($request->from_rsloc) {
                $query->where('rsloc', '!=', $request->from_rsloc);
            }

            $locations = $query->orderBy('rsloc')->get();

            $locations->transform(function ($location) use ($request, $item) {

                // Find inventory in this location
                $inventory = DB::table('rsitemloc')
                    ->where('rssite', $request->rssite)
                    ->where('rswhse', $request->rswhse)
                    ->where('rsbaynum', $request->rsbaynum)
                    ->where('rsloc', $location->rsloc)
                    ->where('qty', '>', 0)
                    ->get();

                // Completely empty location
                if ($inventory->isEmpty()) {

                    $location->status = 'empty';
                    $location->available_qty = 0;

                    return $location;
                }

                // Check if this location contains the same item
                $sameItem = $inventory->where('item', $item);

                if ($sameItem->isNotEmpty()) {

                    $location->status = 'same_item';

                    // Total available quantity of the same item
                    $location->available_qty = $sameItem->sum('qty');

                    // Get pallet numbers
                    $location->pallet_numbers = $sameItem
                        ->pluck('rspallet_num')
                        ->filter(function ($pallet) {
                            return $pallet !== null && trim($pallet) !== '';
                        })
                        ->unique()
                        ->values()
                        ->implode(', ');

                    // ==========================================
                    // GET ITEM PALLET SIZE / LOCATION LIMIT
                    // ==========================================

                    $connections = [
                        'PI-SP'    => 'pisp_con',
                        'FP-SP'    => 'fpsp_con',
                        'PIGRP-SP' => 'pigrpsp_con',
                    ];

                    $connection = $connections[$request->rssite] ?? null;

                    $location->location_limit = 0;

                    if ($connection) {

                        // Get the job from the same item in this location
                        $job = $sameItem->first()->job;

                        $jobDetail = DB::connection($connection)
                            ->table('job as j')
                            ->join('item as i', 'i.item', '=', 'j.item')
                            ->select('i.Uf_Item_PalletSize')
                            ->where('j.job', $job)
                            ->where('j.suffix', 0)
                            ->where('j.item', $item)
                            ->first();

                        if ($jobDetail) {

                            $location->location_limit =
                                (float) ($jobDetail->Uf_Item_PalletSize ?? 0);

                        }
                    }

                    return $location;
                }

                // Different item
                $location->status = 'different_item';
                $location->available_qty = 0;

                return $location;
            });

            // Remove locations containing a different item
            $locations = $locations
                ->whereIn('status', ['empty', 'same_item'])
                ->values();

            return response()->json($locations);
        }

        return response()->json(
            $query->orderBy('rsloc')->get()
        );
    }

    // Get Pallets
    public function getPallets(Request $request)
    {
        $pallets = DB::table('rsitemloc')
            ->select('rspallet_num')
            ->where('rssite', $request->rssite)
            ->where('rswhse', $request->rswhse)
            ->where('rsloc', $request->rsloc)
            ->whereNotNull('rspallet_num')
            ->distinct()
            ->orderBy('rspallet_num')
            ->get();

        return response()->json($pallets);
    }


    // Get Job / CO
    public function getJobs(Request $request)
    {
        $query = DB::table('rsitemloc')
            ->select('job')
            ->where('rssite', $request->rssite)
            ->where('rswhse', $request->rswhse)
            ->where('rsloc', $request->rsloc)
            ->whereNotNull('job')
            ->where('job', '!=', '');

        if ($request->filled('rspallet')) {

            // PALLET SELECTED
            $query->where('rspallet_num', $request->rspallet);

        } else {

            // NO PALLET
            $query->where(function ($q) {
                $q->whereNull('rspallet_num')
                ->orWhere('rspallet_num', '');
            });
        }

        return response()->json(
            $query->distinct()
                ->orderBy('job')
                ->get()
        );
    }
    private function authorizeQuantityMove()
    {
        if (!auth()->user()->hasQuantityMoveAccess()) {
            abort(403, 'Unauthorized');
        }
    }
    // Get Item Details
    public function getItem(Request $request)
    {
        $query = DB::table('rsitemloc')
            ->select(
                'item',
                'desc',
                'qty',
                'um'
            )
            ->where('rssite', $request->rssite)
            ->where('rswhse', $request->rswhse)
            ->where('rsloc', $request->rsloc)
            ->where('job', $request->job);

        if ($request->filled('rspallet')) {

            // PALLET EXISTS
            $query->where('rspallet_num', $request->rspallet);

        } else {

            // NO PALLET
            $query->where(function ($q) {
                $q->whereNull('rspallet_num')
                ->orWhere('rspallet_num', '');
            });
        }

        $item = $query->first();

        return response()->json($item);
    }
}
