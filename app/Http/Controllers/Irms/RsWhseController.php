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
        if (auth()->user()->level > 3) {
            abort(403, 'Unauthorized');
        }
        $user = auth()->user();
        $userSite = $user->rssite;
        $userid = $user->userid;

        if ($userid === 'sa') {
            // Show all warehouses for super admin
            $warehouses = \DB::select('EXEC sp_view_whse', [null]);
        } else {
            $warehouses = \DB::select('EXEC sp_view_whse ?', [$userSite]);
        }

        $sites = IrmsSite::all();
        return view('irms.irms-layouts.warehouse', compact('warehouses', 'sites'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rssite'    => 'required|string|max:8',
            'rswhse'    => 'required|string|max:10',
            'name'      => 'required|string|max:30',
            'addr'      => 'nullable|string|max:60',
        ]);
        if (Rswhse::where('rswhse', $validated['rswhse'])->exists()) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Warehouse code already exists.']);
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
            return redirect()->route('warehouse.index')->with('success', "$whse added successfully.");
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
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

        try {
            \DB::statement('EXEC sp_update_whse ?, ?, ?, ?, ?, ?', [
                $validated['orig_rssite'],
                $validated['orig_rswhse'],
                $validated['rssite'],
                $validated['rswhse'],
                $validated['name'],
                $validated['addr'],
            ]);
            return redirect()->route('warehouse.index')
                ->with('success', 'Warehouse: ' . $validated['rswhse'] . ' updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
