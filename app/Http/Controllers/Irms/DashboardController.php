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

        //Users
        $received = DB::select('EXEC sp_GetGoodsReceivedCount ?', [$userId]);
        $dispatched = DB::select('EXEC sp_GetGoodsDispatchedCount ?', [$userId]);
        $vacantRack = DB::select('EXEC sp_GetWarehouseOccupancy ?', [$userSite]);
        $usersTrans = DB::select('EXEC sp_GetUserTransactions ?', [$userId]);
        $occupiedRack = DB::select('EXEC sp_GetOccupiedRackPercentage ?', [$userSite]);
        //Supervisor/Manager


        $goodsReceivedCount = $received[0]->GoodsReceivedCount ?? 0;
        $goodsDispatchedCount = $dispatched[0]->GoodsDispatchedCount ?? 0;
        $vacantRackPer = $vacantRack[0]->VacantRackPercentage ?? 0;
        $occupiedRackPer = $occupiedRack[0]->OccupiedRackPercentage ?? 0;


        //Weekly Transaction Data
        $weeklyChartData = [
            'labels' => [],
            'data' => [],
            'title' => 'Weekly Transactions'
        ];

        try {
            // 1. Execute the stored procedure (using the correct T-SQL syntax)
            $weeklyDataTransData = DB::select('EXEC sp_get_weekly_transaction_count @UserId = ?, @Site = ?', [$userId, $userSite]);

            // 2. Transform the data only if results exist
            if ($weeklyDataTransData) {
                $weeklyLabels = [];
                $weeklyCounts = [];
                foreach ($weeklyDataTransData as $day) {
                    $weeklyLabels[] = $day->transaction_day;
                    $weeklyCounts[] = $day->total_transaction_count;
                }

                $weeklyChartData['labels'] = $weeklyLabels;
                $weeklyChartData['data'] = $weeklyCounts;
                // The title key is already set above
            }

        } catch (\Exception $e) {
            // Log error here, but continue to use the empty default $weeklyChartData
            // Log::error("Failed to fetch weekly chart data: " . $e->getMessage());
        }



        $chartData = [
            'labels' => ['Received Goods', 'Dispatched Goods'],
            'data' => [$goodsReceivedCount, $goodsDispatchedCount],
            'title' => 'Goods Movement Today'
        ];

        return view('irms.irms-layouts.dashboard', compact(
            'goodsReceivedCount',
            'goodsDispatchedCount',
            'vacantRackPer',
            'chartData',
            'usersTrans',
            'occupiedRackPer',
            'weeklyChartData'
        ));
    }
}
