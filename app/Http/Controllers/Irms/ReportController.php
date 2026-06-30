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
            
        ];

        $records = DB::connection('sqlsrv')->select(
            'EXEC dbo.sp_Stickering_Report ?, ?, ?, ?',
            $params
        );

        return [
            'records'       => $records,
            'rssite'        => $userSite,
            'rswhse'        => $params[1],
            'rsbaynum'      => $params[2],
            'rsloc'         => $params[3],
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

    /*private function getQuarantineData(Request $request)
    {
    try {
        $user = auth()->user();
        $userSite = trim($user->rssite);

        $params = [
            $userSite,
            $request->rswhse ?? null,
            $request->rsbaynum ?? null,
            $request->rsloc ?? null,
        ];

        $records = DB::connection('sqlsrv')->select(
            'EXEC dbo.sp_Quarantine_Report ?, ?, ?, ?',
            $params
        );

        return [
            'records' => $records,
            'rssite' => $userSite,
        ];

    } catch (\Exception $e) {
        dd($e->getMessage(), $e->getTraceAsString());
    }
}*/

    
   /* private function getQuarantineData(Request $request)
    {
        
        $user = auth()->user();
        $userSite = trim($user->rssite);

        $params = [
            $userSite,
            $request->filled('rswhse') ? $request->rswhse : null,
            $request->filled('rsbaynum') ? $request->rsbaynum : null,
            $request->filled('rsloc') ? $request->rsloc : null,

        ];

        dd($params);
        $records = DB::connection('sqlsrv')->select(
            'EXEC dbo.sp_Quarantine_Report ?, ?, ?, ?',
            $params
        );

        return [
            'records'       => $records,
            'rssite'        => $userSite,
            'rswhse'        => $params[1],
            'rsbaynum'      => $params[2],
            'rsloc'         => $params[3],
        ];
        
    }*/

    private function getQuarantineData(Request $request)
{
    $user = auth()->user();
    $userSite = trim($user->rssite);

    $params = [
        $userSite,
        $request->rswhse ?? null,
        $request->rsbaynum ?? null,
    ];

    try {

        $records = DB::connection('sqlsrv')->select(
            'EXEC dbo.sp_Quarantine_Report ?, ?, ?',
            $params
        );

        return [
            'records'       => $records,
            'rssite'        => $userSite,
            'rswhse'        => $params[1],
            'rsbaynum'      => $params[2],
        ];


    } catch (\Throwable $e) {

        // ✅ This will show the REAL error
        dd([
            'ERROR MESSAGE' => $e->getMessage(),
            'LINE' => $e->getLine(),
            'FILE' => $e->getFile(),
        ]);
    }
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
}