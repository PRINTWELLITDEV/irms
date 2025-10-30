<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getGoodsCount(){

        $userId = auth()->id();
        $userSite = auth()->user()->rssite;


        $dispatchedSql = "EXEC sp_getGoodsDispatchedCount ?";
        $dispatchedResult = DB::select($dispatchedSql, [$userId]);

        $goodsDispatchedCount = $dispatchedResult[0]->GoodsDispatchedCount ?? 0;


        $receivedSql = "EXEC sp_getGoodsReceivedCount ?";
        $receivedResult = DB::select($receivedSql, [$userId]);

        $goodsReceivedCount = $receivedResult[0]->GoodsReceivedCount ?? 0;

        $warehouseOccupancySql = "EXEC sp_GetWarehouseOccupancy ?";
        $warehouseOccupancyResult = DB::select($warehouseOccupancySql, [$userSite]);

        $warehouseOccupancyPercentage = $warehouseOccupancyResult[0]->VacantRackPercentage ?? 0;


        return view('irms.irms-layouts.dashboard', [
            'goodsReceivedCount' => $goodsReceivedCount,
            'goodsDispatchedCount' => $goodsDispatchedCount,
            'vacantRackPercentage' => $warehouseOccupancyPercentage
        ]);

    }
}
