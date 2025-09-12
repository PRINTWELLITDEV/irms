<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use app\Models\rswhse;

class RsWhseController extends Controller
{
    public function index()
    {
        // Fetch only the columns you need
        $warehouses = DB::table('rswhse')
            ->select('rssite', 'rswhse', 'name', 'addr')
            ->get();

        return view('irms.irms-layouts.warehouse', compact('warehouses'));
    }
}
