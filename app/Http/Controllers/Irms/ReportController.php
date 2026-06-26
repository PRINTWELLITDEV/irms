<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{

    private function getStickeringData(Request $request)
    {
        $user = auth()->user();
        $userSite = trim($user->rssite);

        $params = [
            $userSite,
            $request->filled('rswhse') ? $request->rswhse : null,
            $request->filled('rsbaynum') ? $request->rsbaynum : null,
            $request->filled('rsloc') ? $request->rsloc : null,
            $request->filled('rspalletnum') ? $request->rspalletnum : null,
            $request->filled('job') ? $request->job : null,
            $request->filled('item') ? $request->item : null,
            $request->filled('desc') ? $request->desc : null,
            $request->filled('um') ? $request->um : null,
            $request->filled('qty') ? $request->qty : null,
            $request->filled('datercvd') ? $request->datercvd : null,
        ];

        $records = DB::connection('sqlsrv')->select(
            'EXEC dbo.sp_Stickering_Report ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
            $params
        );

        return [
            'records'       => $records,
            'rssite'        => $userSite,
            'rswhse'        => $params[1],
            'rsbaynum'      => $params[2],
            'rsloc'         => $params[3],
            'job'           => $params[4],
            'item'          => $params[5],
            'desc'          => $params[6],
            'um'            => $params[7],
            'qty'           => $params[8],
            'datercvd'      => $params[9],
        ];
    }

    
    public function stickeringPreview(Request $request)
    {
        $data = $this->getStickeringData($request);
        $data['showDownload'] = true;

        return view('irms.irms-layouts.stickering-report', $data);
    }

    
    public function stickeringPdf(Request $request)
    {
        $data = $this->getStickeringData($request);
        $data['showDownload'] = false;

        $pdf = Pdf::loadView('irms.irms-layouts.stickering-report', $data)
                ->setPaper('a4', 'landscape');

        return $pdf->download('stickering-report.pdf');
    }

    /*public function stickeringPreview(Request $request)
    {

        $rssite   = auth()->user()->rssite;

        $records = DB::connection('sqlsrv')->select(
            'EXEC dbo.sp_Stickering_Report ?',
            [$rssite]
        );

        return view('irms.irms-layouts.stickering-report', [
            'records'     => $records,
            'rssite'      => $rssite,
            'showDownload'=> true   
        ]);
    }

    public function stickeringPdf(Request $request)
    {
        $rssite   = auth()->user()->rssite;

        $records = DB::connection('sqlsrv')->select(
            'EXEC dbo.sp_Stickering_Report ?',
            [$rssite]
        );

        $pdf = Pdf::loadView('irms.irms-layouts.stickering-report', [
            'records'  => $records,
            'rssite'   => $rssite,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('stickering-report.pdf');
    }*/


    
    private function getQuarantineData(Request $request)
    {
        $user = auth()->user();
        $userSite = trim($user->rssite);

        $params = [
            $userSite,
            $request->filled('rswhse') ? $request->rswhse : null,
            $request->filled('rsbaynum') ? $request->rsbaynum : null,
            $request->filled('rsloc') ? $request->rsloc : null,
            $request->filled('rspalletnum') ? $request->rspalletnum : null,
            $request->filled('job') ? $request->job : null,
            $request->filled('item') ? $request->item : null,
            $request->filled('desc') ? $request->desc : null,
            $request->filled('um') ? $request->um : null,
            $request->filled('qty') ? $request->qty : null,
            $request->filled('datercvd') ? $request->datercvd : null,

        ];

        $records = DB::connection('sqlsrv')->select(
            'EXEC dbo.sp_Quarantine_Report ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
            $params
        );

        return [
            'records'       => $records,
            'rssite'        => $userSite,
            'rswhse'        => $params[1],
            'rsbaynum'      => $params[2],
            'rsloc'         => $params[3],
            'rspallet_num'  => $params[4],
            'job'           => $params[5],
            'item'          => $params[6],
            'desc'          => $params[7],
            'um'            => $params[8],
            'qty'           => $params[9],
            'datercvd'      => $params[10],
        ];
    }

    
    public function quarantinePreview(Request $request)
    {
        $data = $this->getQuarantineData($request);
        $data['showDownload'] = true;

        return view('irms.irms-layouts.quarantine-report', $data);
    }

    
    public function quarantinePdf(Request $request)
    {
        $data = $this->getQuarantineData($request);
        $data['showDownload'] = false;

        $pdf = Pdf::loadView('irms.irms-layouts.quarantine-report', $data)
                ->setPaper('a4', 'landscape');

        return $pdf->download('quarantine-report.pdf');
    }


    /*
    public function quarantinePreview(Request $request)
    {
        $user = auth()->user();
        $userSite = trim($user->rssite);
        $rswhse   = $request->rswhse ?: null;
        $rsbaynum = $request->rsbaynum ?: null;
        $rsloc    = $request->rsloc ?: null;
        $rspallet_num    = $request->rspalletnum ?: null;
        $job    = $request->job ?: null;
        $item    = $request->item ?: null;
        $desc    = $request->desc ?: null;
        $um    = $request->um ?: null;
        $qty    = $request->qty ?: null;
        $datercvd    = $request->datercvd ?: null;

        $records = DB::connection('sqlsrv')->select(
            'EXEC dbo.sp_Quarantine_Report ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
            [
                $userSite, 
                $rswhse, 
                $rsbaynum, 
                $rsloc,
                $rspallet_num,
                $job,
                $item,
                $desc,
                $um,
                $qty,
                $datercvd
            ]
        );

        return view('irms.irms-layouts.quarantine-report', [
            'records'       => $records,
            'rssite'        => $userSite,
            'rswhse'        => $rswhse,
            'rsbaynum'      => $rsbaynum,
            'rspallet_num'  => $rspallet_num,
            'job'           => $job,
            'item'          => $item,
            'desc'          => $desc,
            'um'            => $um,
            'qty'           => $qty,
            'datercvd'      => $datercvd,
            'showDownload'=> true   
        ]);
    }
    public function quarantinePdf(Request $request)
    {
        $user = auth()->user();
        $userSite = trim($user->rssite);
        $rswhse   = $request->rswhse ?: null;
        $rsbaynum = $request->rsbaynum ?: null;
        $rsloc    = $request->rsloc ?: null;
        $rspallet_num    = $request->rspalletnum ?: null;
        $job    = $request->job ?: null;
        $item    = $request->item ?: null;
        $desc    = $request->desc ?: null;
        $um    = $request->um ?: null;
        $qty    = $request->qty ?: null;
        $datercvd    = $request->datercvd ?: null;

        $records = DB::connection('sqlsrv')->select(
            'EXEC dbo.sp_Quarantine_Report ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
            [
                $userSite, 
                $rswhse, 
                $rsbaynum, 
                $rsloc,
                $rspallet_num,
                $job,
                $item,
                $desc,
                $um,
                $qty,
                $datercvd
            ]
        );

        $pdf = Pdf::loadView('irms.irms-layouts.quarantine-report', [
            'records'       => $records,
            'rssite'        => $userSite,
            'rswhse'        => $rswhse,
            'rsbaynum'      => $rsbaynum,
            'rspallet_num'  => $rspallet_num,
            'job'           => $job,
            'item'          => $item,
            'desc'          => $desc,
            'um'            => $um,
            'qty'           => $qty,
            'datercvd'      => $datercvd,
            'showDownload'=> true   
        ])->setPaper('a4', 'landscape');

        return $pdf->download('quarantine-report.pdf');
    }*/
}