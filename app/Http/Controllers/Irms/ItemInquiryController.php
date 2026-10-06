<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\IrmsSite;

class ItemInquiryController extends Controller
{
    /*ITEM INQUIRY PAGE*/
    public function index()
    {
        // if (auth()->user()->level > 3) {
        //     abort(401, 'Unauthorized');
        // }
    /**
     * Restricts access to Item Inquiry based on user permission. Even using URL to search would not work if user does not have access.
     * Author: Jim Dominic Pabalate
     * Date Created: September 28, 2026
     */        if (!auth()->user()->hasItemInquiryAccess()) {
        abort(403, 'Unauthorized');
    }

        

        return view('irms.irms-layouts.item-inquiry');
    }

    /*To Automatically get the USER SITE assign in their account*/
    private function getUserSite()
    {
        if (auth()->user()->level == 1) {
            return null;
        }

        $site = auth()->user()->rssite;

        if (!$site) {
            abort(403, 'No site is assigned to this user.');
        }

        return $site;
    }

    /*USER SITE INFO FOR PDF*/
    private function getUserSiteInfo()
    {
        $user = auth()->user();

        // Level 1
        if ($user->level == 1) {
            return [
                'rssite'    => 'ALL',
                'site_desc' => 'All Sites',
            ];
        }

        $userSite = IrmsSite::where('rssite', $user->rssite)->first();

        return [
            'rssite'    => $user->rssite,
            'site_desc' => $userSite
                ? $userSite->rssite_desc
                : $user->rssite,
        ];
    }

    /*ITEM INQUIRY TABLE*/
    public function reportList(Request $request)
    {
        $request->validate([
            'viewType'  => 'required|in:detailed,summary',
            'item'      => 'nullable|string|max:100',
            'co'        => 'nullable|string|max:100',
            'warehouse' => 'nullable|string|max:100',
            'bay'       => 'nullable|string|max:100',
            'status'    => 'nullable|string|max:100',
        ]);

        $item = trim($request->input('item', ''));
        $co   = trim($request->input('co', ''));

        /*
        |--------------------------------------------------------------------------
        | DETAILED
        |--------------------------------------------------------------------------
        */
        if ($request->viewType === 'detailed') {

            $warehouse = trim($request->input('warehouse', ''));
            $bay       = trim($request->input('bay', ''));
            $status    = trim($request->input('status', ''));

            $data = $this->getDetailedData(
                $item,
                $co,
                $warehouse,
                $bay,
                $status
            );

            return view(
                'irms.irms-tables.item-inquiry-detailed',
                compact('data')
            )->render();
        }


        /*
        | SUMMARY
        */
        $data = $this->getSummaryData($item, $co);

        return view(
            'irms.irms-tables.item-inquiry-summary',
            compact('data')
        )->render();
    }

    /*ITEM AUTOCOMPLETE*/
    public function itemSuggestions(Request $request)
    {
        $site = $this->getUserSite();

        $search = trim($request->input('term', ''));

        if ($search === '') {
            return response()->json([]);
        }

        $query = DB::table('rsitemloc')
            ->select('item')
            ->whereNotNull('item')
            ->where('item', 'LIKE', $search . '%');

        /*
        |--------------------------------------------------------------------------
        | SITE FILTER
        |--------------------------------------------------------------------------
        */
        if ($site !== null) {
            $query->where('rssite', $site);
        }

        return response()->json(
            $query
                ->distinct()
                ->orderBy('item')
                ->limit(10)
                ->pluck('item')
        );
    }
public function coSuggestions(Request $request)
{
    $term = trim($request->get('term', ''));
    $item = trim($request->get('item', ''));

    if ($term === '' || $item === '') {
        return response()->json([]);
    }

    $cos = DB::connection('sqlsrv')
        ->table('rsitemloc')
        ->where('item', $item)
        ->where('job', 'LIKE', '%' . $term . '%')
        ->select('job')
        ->distinct()
        ->orderBy('job')
        ->limit(20)
        ->pluck('job');

    return response()->json($cos);
}
    /*
    GET DETAILED DATA
    */
    private function getDetailedData($item = '', $co = '', $warehouse = '', $bay = '', $status = '') {
        $site = $this->getUserSite();

        $statusExpression = "
            CASE
                WHEN rspallet_num LIKE '%WIP%'
                    THEN 'WIP'

                WHEN rspallet_num LIKE '%FOR STICK%'
                    OR rspallet_num LIKE '%STICKERING%'
                    THEN 'FOR STICKERING'

                WHEN rspallet_num LIKE '%JIT%'
                    THEN 'JIT FOR STICKERING'

                ELSE 'WITH STICKER'
            END
        ";

        $query = DB::table('rsitemloc')
            ->select([
                'rswhse as warehouse',
                'job as co',
                'item',
                DB::raw('[desc] as item_description'),
                'um',
                'qty',
                'rsloc as location',
                'datercvd as date_received',

                DB::raw("$statusExpression AS status")
            ]);

        // SITE
        if ($site !== null) {
            $query->where('rssite', $site);
        }

        // ITEM
        if ($item !== '') {
            $query->where(
                'item',
                'LIKE',
                '%' . $item . '%'
            );
        }

        // CO
        if ($co !== '') {
            $query->where(
                'job',
                'LIKE',
                '%' . $co . '%'
            );
        }

        // WAREHOUSE
        if ($warehouse !== '') {
            $query->where('rswhse', $warehouse);
        }

        // BAY
        if ($bay !== '') {
            $query->where('rsbaynum', $bay);
        }

        // STATUS
        if ($status !== '') {
            $query->whereRaw(
                "$statusExpression = ?",
                [$status]
            );
        }

        return $query
            ->orderBy('rswhse')
            ->orderBy('job')
            ->orderBy('item')
            ->orderBy('rsloc')
            ->get();
    }

    /*
    GET SUMMARY DATA
    */
    private function getSummaryData($item = '', $co = '')
    {
        $site = $this->getUserSite();

        $statusExpression = "
            CASE
                WHEN rspallet_num LIKE '%WIP%'
                    THEN 'WIP'

                WHEN rspallet_num LIKE '%FOR STICK%'
                     OR rspallet_num LIKE '%STICKERING%'
                    THEN 'FOR STICKERING'

                WHEN rspallet_num LIKE '%JIT%'
                    THEN 'JIT FOR STICKERING'

                ELSE 'WITH STICKER'
            END
        ";

        $query = DB::table('rsitemloc')
            ->select([
                'rswhse as warehouse',
                'job as co',
                'item',
                DB::raw('[desc] as item_description'),
                'um',
                DB::raw('SUM(qty) as qty'),
                'rsbaynum as bay_no',
                DB::raw("$statusExpression AS status")
            ]);


        /*
        |--------------------------------------------------------------------------
        | SITE FILTER
        |--------------------------------------------------------------------------
        */
        if ($site !== null) {
            $query->where('rssite', $site);
        }


        /*
        |--------------------------------------------------------------------------
        | ITEM FILTER
        |--------------------------------------------------------------------------
        */
        if ($item !== '') {
            $query->where(
                'item',
                'LIKE',
                '%' . $item . '%'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CO / JOB FILTER
        |--------------------------------------------------------------------------
        */
        if ($co !== '') {
            $query->where(
                'job',
                'LIKE',
                '%' . $co . '%'
            );
        }


        return $query
            ->groupBy(
                'rswhse',
                'job',
                'item',
                'desc',
                'um',
                'rsbaynum',
                DB::raw($statusExpression)
            )
            ->orderBy('rswhse')
            ->orderBy('job')
            ->orderBy('item')
            ->orderBy('rsbaynum')
            ->get();
    }

    /*DOWNLOAD DETAILED PDF based on what user click per row uses a collapase table to show the details of the item */
    public function downloadDetailedPdf(Request $request)
    {
        $request->validate([
            'item'      => 'nullable|string|max:100',
            'co'        => 'nullable|string|max:100',
            'warehouse' => 'nullable|string|max:100',
            'bay'       => 'nullable|string|max:100',
            'status'    => 'nullable|string|max:100',
        ]);
 
        $item      = trim($request->input('item', ''));
        $co        = trim($request->input('co', ''));
        $warehouse = trim($request->input('warehouse', ''));
        $bay       = trim($request->input('bay', ''));
        $status    = trim($request->input('status', ''));
 
        $data = $this->getDetailedData(
            $item,
            $co,
            $warehouse,
            $bay,
            $status
        );
 
        $siteInfo = $this->getUserSiteInfo();
 
        $pdf = Pdf::loadView(
            'irms.irms-layouts.pdf.item-inquiry-detailed',
            [
                'data'      => $data,
                'item'      => $item,
                'co'        => $co,
                'warehouse' => $warehouse,
                'bay'       => $bay,
                'status'    => $status,
                'site'      => $siteInfo['rssite'],
                'site_desc' => $siteInfo['site_desc'],
            ]
        );
 
        $pdf->setPaper('A4', 'portrait');
        // ================================================================================
        // Bottom-left footer of PDF with page number, print date, printed by, and file name
        // ================================================================================
        $canvas = $pdf->getDomPDF()->getCanvas();

        $font = $pdf->getDomPDF()
            ->getFontMetrics()
            ->get_font('DejaVu Sans', 'normal');

        $gray = [0, 0, 0];


        $canvas->page_text(
            15,
            790,
            'Page {PAGE_NUM} of {PAGE_COUNT}',
            $font,
            5,
            $gray
        );

        $canvas->page_text(
            15,
            795,
            'PRINT DATE: ' . now()->format('M d, Y'),    
            $font,
            5,
            $gray
        );

        $canvas->page_text(
            15,
            800,
            'PRINTED BY: ' . (auth()->user()->name ?? auth()->user()->userid),
            $font,
            5,
            $gray
        );

        $canvas->page_text(
            15,
            805,
            'FILE: ' . pathinfo('item-inquiry-detailed.pdf', PATHINFO_FILENAME),
            $font,
            5,
            $gray
        );
        return $pdf->download('item-inquiry-detailed.pdf');
    }

    /*DOWNLOAD SUMMARY PDF to show the summary of the item */
    public function downloadSummaryPdf(Request $request)
    {
        $item = trim($request->input('item', ''));
        $co   = trim($request->input('co', ''));

        $data = $this->getSummaryData($item, $co);

        $siteInfo = $this->getUserSiteInfo();

        $pdf = Pdf::loadView(
            'irms.irms-layouts.pdf.item-inquiry-summary',
            [
                'data'      => $data,
                'item'      => $item,
                'co'        => $co,
                'site'      => $siteInfo['rssite'],
                'site_desc' => $siteInfo['site_desc'],
            ]
        );

        $pdf->setPaper('A4', 'portrait');
        // ================================================================================
        // Bottom-left footer of PDF with page number, print date, printed by, and file name
        // ================================================================================
        $canvas = $pdf->getDomPDF()->getCanvas();

        $font = $pdf->getDomPDF()
            ->getFontMetrics()
            ->get_font('DejaVu Sans', 'normal');

        $gray = [0, 0, 0];


        $canvas->page_text(
            15,
            790,
            'Page {PAGE_NUM} of {PAGE_COUNT}',
            $font,
            5,
            $gray
        );

        $canvas->page_text(
            15,
            795,
            'PRINT DATE: ' . now()->format('M d, Y'),    
            $font,
            5,
            $gray
        );

        $canvas->page_text(
            15,
            800,
            'PRINTED BY: ' . (auth()->user()->name ?? auth()->user()->userid),
            $font,
            5,
            $gray
        );

        $canvas->page_text(
            15,
            805,
            'FILE: ' . pathinfo('item-inquiry-summary.pdf', PATHINFO_FILENAME),
            $font,
            5,
            $gray
        );

        return $pdf->download('item-inquiry-summary.pdf');
                return $pdf->download('item-inquiry-summary.pdf');
    }
}