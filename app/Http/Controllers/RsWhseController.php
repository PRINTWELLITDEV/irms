<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Rswhse; // Add this at the top

class RsWhseController extends Controller
{
    public function index()
    {
        $warehouses = \DB::select('EXEC sp_view_whse');
        return view('irms.irms-layouts.warehouse', compact('warehouses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rssite'    => 'required|string|max:8',
            'rswhse'    => 'required|string|max:10',
            'name'      => 'required|string|max:30',
            'addr'      => 'nullable|string|max:60',
        ]);

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
            return redirect()->route('warehouse.index')->with('success', 'Warehouse added successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // public function edit($name){
    //     $item = Item::findOrFail($name);
    //     return view('items.edit', compact('item'))
    // }
}
