<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    //Get Stickering Report Data
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

    
    //Stickering Report Preview
    public function stickeringPreview(Request $request)
    {
        $data = $this->getStickeringData($request);
        $data['showDownload'] = true;

        return view('irms.irms-layouts.stickering-report', $data);
    }


    //Stickering Report Download PDF
    public function stickeringPdf(Request $request)
    {
        $data = $this->getStickeringData($request);
        $data['showDownload'] = false;

        $pdf = Pdf::loadView('irms.irms-layouts.stickering-report', $data)
                ->setPaper('a4', 'landscape');

        return $pdf->download('stickering-report.pdf');
    }



    //Get Quarantine Report Data
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
        dd([
            'ERROR MESSAGE' => $e->getMessage(),
            'LINE' => $e->getLine(),
            'FILE' => $e->getFile(),
        ]);
    }
}


    //Quarantine Report Preview
    public function quarantinePreview(Request $request)
    {
        $data = $this->getQuarantineData($request);
        $data['showDownload'] = true;

        return view('irms.irms-layouts.quarantine-report', $data);
    }
    

    //Quarantine Report Download PDF
    public function quarantinePdf(Request $request)
    {
        $data = $this->getQuarantineData($request);
        $data['showDownload'] = false;

        $pdf = Pdf::loadView('irms.irms-layouts.quarantine-report', $data)
                ->setPaper('a4', 'landscape');

        return $pdf->download('quarantine-report.pdf');
    }



    //Get WIP Report Data
    private function getWIPData(Request $request)
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
            'EXEC dbo.sp_WIP_Report ?, ?, ?, ?',
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
    

    //WIP Report Preview
    public function WIPPreview(Request $request)
    {
        $data = $this->getWIPData($request);
        $data['showDownload'] = true;

        return view('irms.irms-layouts.wip-report', $data);
    }
    

    //WIP Report Download PDF
    public function WIPPdf(Request $request)
    {
        $data = $this->getWIPData($request);
        $data['showDownload'] = false;

        $pdf = Pdf::loadView('irms.irms-layouts.wip-report', $data)
                ->setPaper('a4', 'landscape');

        return $pdf->download('wip-report.pdf');
    }
}