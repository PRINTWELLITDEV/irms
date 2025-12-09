<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\IrmsSite;
use App\Models\RsUser;
use App\Models\RsLocation;


class RsLocationController extends Controller
{
    public function index()
    {
        if (auth()->user()->level > 3 && auth()->user()->level <= 0) {
            abort(401, 'Unauthorized');
        }

        $sites = IrmsSite::all();
        $warehouses = DB::table('rswhse')->get();
        
        // Update the bay query to include rswhse
        $baynums = DB::table('rsbayloc')
            ->select('rsbaynum', 'rssite', 'rswhse')
            ->get();
            
        return view('irms.irms-layouts.rack-locations', compact('sites', 'warehouses', 'baynums'));
    }

    public function rackList(Request $request)
    {
        $user = auth()->user();
        $userSite = $user->rssite;
        // $userid = $user->userid;

        if (auth()->user()->level == 1) {
            $racklocs = \DB::select('EXEC sp_view_rslocs', [null]);
        } else {
            $racklocs = \DB::select('EXEC sp_view_rslocs ?', [$userSite]);
        }

        // return response()->json($racklocs);
        return view('irms.irms-tables.rack-list', compact('racklocs'))->render();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rssite' => 'required|max:8',
            'rswhse' => 'required|max:10',
            'rsbaynum' => 'required|max:5',
            'rsloc' => 'required|max:15',
            'rsdesc' => 'nullable|max:13',
        ], [
            'rssite.required' => 'The Site field is required.',
            'rssite.max' => 'The Site field must not exceed 8 characters.',
            'rswhse.required' => 'The Warehouse field is required.',
            'rswhse.max' => 'The Warehouse field must not exceed 10 characters.',
            'rsbaynum.required' => 'The Bay Number field is required.',
            'rsbaynum.max' => 'The Bay Number field must not exceed 5 characters.',
            'rsloc.required' => 'The Rack Location field is required.',
            'rsloc.max' => 'The Rack Location field must not exceed 15 characters.',
            'rsdesc.max' => 'The Description field must not exceed 13 characters.',
        ]);

        if (RsLocation::where('rsloc', $validated['rsloc'])
                      ->where('rssite', $validated['rssite'])
                      ->exists()) {
            return response()->json(['message' => 'Rack location already exists for this site.'], 422);
        }

        $rack = $validated['rsloc'];
        $createdate = now();
        $createdby = auth()->user()->userid ?? 'system';

        try {
            \DB::statement('EXEC sp_add_rslocs ?, ?, ?, ?, ?, ?, ?, ?', [
                $validated['rssite'],
                $validated['rswhse'],
                $validated['rsbaynum'],
                $validated['rsloc'],
                $validated['rsdesc'],
                0, // Set quantity to 0
                $createdate,
                $createdby
            ]);
            return response()->json(['message' => "Rack $rack added successfully!"]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function rackItems(Request $request)
    {
        $rssite = $request->input('rssite') ? $request->input('rssite') : auth()->user()->rssite;
        $rsloc = $request->input('rsloc');
        $items = \DB::select(
            'SELECT rssite, rsloc, job, item, [desc], qty, um FROM rsitemloc WHERE rssite=? AND rsloc = ?', 
            [$rssite, $rsloc]
        );
        return response()->json($items);
    }

    public function rackMapGrid(Request $request)
    {
        $rssite = $request->input('rssite');
        $rswhse = $request->input('rswhse');
        $rsbaynum = $request->input('rsbaynum');

        // Get rack locations with job info
        $locations = \DB::select('EXEC sp_rack_map ?, ?, ?', [$rssite, $rswhse, $rsbaynum]);

        // Group locations by rsloc to handle multiple jobs per location
        $grouped = [];
        foreach ($locations as $location) {
            $rsloc = $location->rsloc;
            if (!isset($grouped[$rsloc])) {
                $grouped[$rsloc] = [
                    'rssite' => $location->rssite,
                    'rswhse' => $location->rswhse,
                    'rsbaynum' => $location->rsbaynum,
                    'rsloc' => $location->rsloc,
                    'rsdesc' => $location->rsdesc,
                    'qty' => $location->qty,
                    'createdate' => $location->createdate,
                    'jobs' => [],
                    'original_qty' => 0
                ];
            }
            
            // Add job to the list if exists
            if ($location->job && $location->item) {
                $grouped[$rsloc]['jobs'][] = [
                    'job' => $location->job,
                    'item' => $location->item
                ];
            }
        }

        // Map rssite to connection name
        $connections = [
            'PI-SP' => 'pisp_con',
            'FP-SP' => 'fpsp_con',
            'PIGRP-SP' => 'pigrpsp_con',
        ];
        $connection = $connections[$rssite] ?? null;

        // Fetch original pallet sizes for all jobs
        foreach ($grouped as $rsloc => &$data) {
            if ($connection && !empty($data['jobs'])) {
                foreach ($data['jobs'] as $jobData) {
                    try {
                        $jobDetail = \DB::connection($connection)
                            ->table('job as j')
                            ->join('item as i', 'i.item', '=', 'j.item')
                            ->select('i.Uf_Item_PalletSize')
                            ->where('j.job', $jobData['job'])
                            ->where('j.suffix', 0)
                            ->where('j.item', $jobData['item'])
                            ->first();

                        if ($jobDetail) {
                            $data['original_qty'] += floatval($jobDetail->Uf_Item_PalletSize ?? 0);
                        }
                    } catch (\Exception $e) {
                        // If connection fails, keep original_qty as accumulated value
                    }
                }
            }
        }

        // Convert back to array for JSON response
        $result = array_values($grouped);

        return response()->json($result);
    }

    
}
