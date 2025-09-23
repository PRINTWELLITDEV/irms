<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RsGoodsReceivingController extends Controller
{
    /**
     * Show the goods receiving form.
     */
    public function index()
    {
        // You can pass warehouses, bays, etc. to the view if needed
        return view('irms.irms-layouts.whse-goodsreceiving');
    }

    /**
     * Process the goods receiving form submission.
     */
    public function process(Request $request)
    {
        $validated = $request->validate([
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
        // \DB::table('goods_receiving')->insert($validated);

        return redirect()->back()->with('success', 'Goods receiving processed successfully!');
    }
}
