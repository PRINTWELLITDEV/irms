@extends('irms/irms-partials.app')

@section('title', 'Item Inquiry')

@section('content')

<main class="app-main" id="itemInquiryPage">
    <div class="app-content-wrapper">

        <div class="app-content-header">
            <div class="container-lg">
                <div class="row align-items-center">
                    <div class="col mb-3 d-flex align-items-center">
                        <h1 class="d-inline-block mb-0 me-3">Item Inquiry</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-lg">

                <!-- FILTER CARD -->
                <div class="row mb-3">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">

                                <div class="row g-3 align-items-center">

                                    <div class="col-md-4">
                                        <label for="item" class="form-label">Item</label>
                                        <span class="text-danger">*</span>

                                        <div class="position-relative">
                                            <input
                                                type="text"
                                                id="item"
                                                class="form-control"
                                                placeholder="Search by item code"
                                                autocomplete="off">

                                            <div
                                                id="itemSuggestions"
                                                class="list-group position-absolute w-100 shadow-sm"
                                                style="z-index: 1050; display: none;">
                                            </div>
                                            <small class="text-muted">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Start typing to search for an item.
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                            <div class="position-relative">
                                            <label for="co" class="form-label">CO</label>
                                            <span class="text-danger">*</span>

                                            <input
                                                type="text"
                                                id="co"
                                                class="form-control"
                                                placeholder="e.g. F2506-0720">
                                            <small class="text-muted">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Matches results automatically as you type
                                            </small>
                                        </div>

                                    </div>
                                    <div class="col-lg-2 col-md-12">


                                         <button
                                            type="button"
                                            id="btnReset"
                                            class="btn btn-outline-secondary w-100">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                                            Clear
                                        </button>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>


                <!-- ITEM INQUIRY REPORT  -->
                <div class="row">
                    <div class="col">
                        <div class="card">

                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold" style="font-size: 25px;">Summary</h6>
                                    
                                    <button type="button"
                                            id="btnDownloadSummary"
                                            class="btn btn-success btn-sm d-none">
                                        <i class="bi bi-file-earmark-pdf me-1"></i>
                                        Download Summary
                                    </button>
                            </div>

                            <div class="card-body">

                              

                                <!-- Summary table -->
                                <div id="summaryReport" class="table-responsive">
                                    <table
                                        id="item-inquiry-summary-table"
                                        class="table table-striped table-bordered table-hover align-middle display">
                                        <thead>
                                            <tr>
                                                <th>Warehouse</th>
                                                <th>CO</th>
                                                <th>Item</th>
                                                <th>Item Description</th>
                                                <th>U/M</th>
                                                <th>Qty</th>
                                                <th>Status</th>
                                                <th>Bay No.</th>

                                            </tr>
                                        </thead>
                                        <tbody id="itemInquirySummaryTable">
                                            <tr>
                                                <td colspan="8" class="text-center text-muted py-4">
                                                    Enter Item and CO to view the summary.
                                                </td>
                                            </tr>
                                        </tbody>

                                    </table>

                                </div>

                               


                             
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</main>
<script>
    //TO PASS THE LARAVEL ROUTES TO JAVASCRIPT
    window.itemInquiryRoutes = {
        reportList:  @json(route('irms.item-inquiry.report-list')),
        pdfSummary:  @json(route('irms.item-inquiry.pdf.summary')),
        pdfDetailed: @json(route('irms.item-inquiry.pdf.detailed')),
        itemSuggestions: @json(route('irms.item-inquiry.item-suggestions')),

    };
</script>
<style>
        #item-inquiry-summary-table tbody tr:not([colspan]) {
        cursor: pointer;
        }
        #item-inquiry-summary-table tbody tr.detail-shown {
            background-color: rgba(13, 110, 253, 0.06);
        }
        .detail-panel {
    background-color: #f8f9fc;
    border-left: 3px solid #0d6efd;
}
.detail-inner-table thead th {
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: .03em;
    color: #6c757d;
}
.detail-inner-table td {
    font-size: 0.875rem;
}
#item-inquiry-summary-table tbody tr.detail-shown .row-expand-icon {
    color: #0d6efd;
}
        </style>
@endsection