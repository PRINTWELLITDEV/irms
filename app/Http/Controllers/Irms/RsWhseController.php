<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\IrmsSite;
use App\Models\RsUser;
use App\Models\Rswhse; 


class RsWhseController extends Controller
{
    public function index()
    {
        if (auth()->user()->level > 3 && auth()->user()->level <= 0) {
            abort(401, 'Unauthorized');
        }
        // $user = auth()->user();
        // $userSite = $user->rssite;
        // $userid = $user->userid;

        // if ($userid === 'sa') {
        //     // Show all warehouses for super admin
        //     $warehouses = \DB::select('EXEC sp_view_whse', [null]);
        // } else {
        //     $warehouses = \DB::select('EXEC sp_view_whse ?', [$userSite]);
        // }

        $sites = IrmsSite::all();
        // return view('irms.irms-layouts.warehouse', compact('warehouses', 'sites'));
        return view('irms.irms-layouts.warehouse', compact('sites'));
    }

    public function whseList(Request $request)
    {
        $user = auth()->user();
        $userSite = $user->rssite;
        // $userid = $user->userid;

        if (auth()->user()->level == 1) {
            $warehouses = \DB::select('EXEC sp_view_whse', [null]);
        } else {
            $warehouses = \DB::select('EXEC sp_view_whse ?', [$userSite]);
        }

        return response()->json($warehouses);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rssite'    => 'required|string|max:8',
            'rswhse'    => 'required|string|max:10',
            'name'      => 'required|string|max:30',
            'addr'      => 'nullable|string|max:60',
        ], [
            'rssite.required' => 'The Site field is required.',
            'rswhse.unique' => 'Warehouse code already exists.',
            'rswhse.required' => 'The Warehouse Code field is required.',
            'name.required' => 'The Warehouse Name field is required.',
            'addr.max' => 'The Address field must not exceed 60 characters.',
        ]);

        if (Rswhse::where('rswhse', $validated['rswhse'])
                    ->where('rssite', $validated['rssite'])
                    ->exists()) {
            // return redirect()->back()->withInput()->withErrors(['error' => 'Warehouse code already exists.']);
            return response()->json(['message' => 'Warehouse code already exists for this site.'], 422);
        }

        $whse = $validated['rswhse'];
        $createdby = auth()->user()->userid ?? 'system';

        try {
            $sql = "EXEC sp_add_whse ?, ?, ?, ?, ?";
            \DB::statement($sql, [
                $validated['rssite'],
                $validated['rswhse'],
                $validated['name'],
                $validated['addr'],
                $createdby,
            ]);
            // return redirect()->route('warehouse.index')->with('success', "$whse added successfully.");
            return response()->json(['message' => "$whse added successfully."]);
        } catch (\Exception $e) {
            // return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'orig_rssite' => 'required|string|max:8',
            'orig_rswhse' => 'required|string|max:10',
            'rssite'      => 'required|string|max:8',
            'rswhse'      => 'required|string|max:10',
            'name'        => 'required|string|max:30',
            'addr'        => 'nullable|string|max:60',
        ]);

        $whse = $validated['rswhse'];

        try {
            \DB::statement('EXEC sp_update_whse ?, ?, ?, ?, ?, ?', [
                $validated['orig_rssite'],
                $validated['orig_rswhse'],
                $validated['rssite'],
                $validated['rswhse'],
                $validated['name'],
                $validated['addr'],
            ]);
            // return redirect()->route('warehouse.index')
            //     ->with('success', 'Warehouse: ' . $validated['rswhse'] . ' updated successfully');
            return response()->json(['message' => "$whse updated successfully."]);
        } catch (\Exception $e) {
            // return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    
}
