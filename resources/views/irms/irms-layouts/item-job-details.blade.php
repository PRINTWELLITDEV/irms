@extends('irms/irms-partials.app')
@section('title', 'IRMS Item Job Details')
@section('content')
<main class="app-main">
    <div class="app-content-wrapper">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col mb-3 d-flex align-items-center">
                        <h1 class="d-inline-block mb-0 me-3">Item Locations</h1>
                        @if(session('success'))
                            <div id="success-alert" class="alert alert-success py-1 px-3 mb-0" style="transition: opacity 0.7s;">
                                {{ session('success') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="card mb-4">
                    <div class="card-header">
                        <a href="{{ route('irms.itemlocations') }}" class="btn border border-secondary">&larr; Back</a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($summary)
                                <div class="row mb-2">
                                    <div class="col-12"><strong>Site:</strong> {{ $summary->rssite_desc ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6"><strong>Job:</strong> {{ $summary->job ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6"><strong>Warehouse:</strong> {{ $summary->rswhse ?? '' }}</div>
                                    <div class="col-6"><strong>Bay:</strong> {{ $summary->rsbaynum ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6"><strong>Item:</strong> {{ $summary->item ?? '' }}</div>
                                    <div class="col-6"><strong>Total Qty:</strong> {{ number_format($summary->totalqty ?? 0, 0) }} {{ $summary->um ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12"><strong>Description:</strong> {{ $summary->desc ?? '' }}</div>
                                </div>
                            @endif
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered align-middle" id="job-details-table">
                                <thead>
                                    <tr>
                                        <th>Rack Location</th>
                                        <th>Pallet No.</th>
                                        <th>Quantity</th>
                                        <th>U/M</th>
                                        <th>Received By</th>
                                        <th>Date Received</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($details as $row)
                                        <tr>
                                            <td>{{ $row->rsloc }}</td>
                                            <td>{{ $row->rspallet_num }}</td>
                                            <td class="text-end">{{ number_format($row->qty ?? 0, 0) }}</td>
                                            <td>{{ $row->um }}</td>
                                            <td>{{ $row->rcvd_by }}</td>
                                            <td>{{ $row->datercvd ? \Carbon\Carbon::parse($row->datercvd)->format('Y-m-d') : '' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No rack details found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection