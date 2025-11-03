<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        $userSite = auth()->user()->rssite;

        // Call your stored procedures
        $received = DB::select('EXEC sp_GetGoodsReceivedCount ?', [$userId]);
        $dispatched = DB::select('EXEC sp_GetGoodsDispatchedCount ?', [$userId]);
        $vacantRack = DB::select('EXEC sp_GetWarehouseOccupancy ?', [$userSite]);

        // Extract the results (handle empty results safely)
        $goodsReceivedCount = $received[0]->GoodsReceivedCount ?? 0;
        $goodsDispatchedCount = $dispatched[0]->GoodsDispatchedCount ?? 0;
        $vacantRackPer = $vacantRack[0]->VacantRackPercentage ?? 0;



        return view('irms.irms-layouts.dashboard', compact(
            'goodsReceivedCount',
            'goodsDispatchedCount',
            'vacantRackPer'
        ));
    }
}
