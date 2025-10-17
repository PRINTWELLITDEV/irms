<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RsTransController extends Controller
{
    public function index()
    {
        if (auth()->user()->level > 3) {
            abort(401, 'Unauthorized');
        }
        $user = auth()->user();
        $userSite = $user->rssite;
        $userid = $user->userid;

        if ($userid === 'sa') {
            $transactions = \DB::select('EXEC sp_view_rstrans', [null]);
        } else {
            $transactions = \DB::select('EXEC sp_view_rstrans ?', [$userSite]);
        }

        return view('irms.irms-layouts.transactions', compact('transactions'));
    }
}
