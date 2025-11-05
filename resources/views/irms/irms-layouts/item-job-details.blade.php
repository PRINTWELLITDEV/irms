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
                        <!-- <a href="{{ route('irms.itemlocations') }}" class="btn border border-secondary">&larr; Back</a> -->
                        <button type="button" class="btn border border-secondary" onclick="window.history.back();">&larr; Back</button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($summary)
                                <div class="row mb-2">
                                    <div class="col-12"><strong>Site:</strong> {{ $summary->rssite_desc ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12"><strong>Warehouse:</strong> {{ $summary->rswhse ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12"><strong>Job: {{ $summary->job ?? '' }} </strong></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12"><strong>Item:</strong> {{ $summary->item ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12"><strong>Description:</strong> {{ $summary->desc ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12"><strong>Total Qty:</strong> {{ number_format($summary->totalqty ?? 0, 0) }} {{ $summary->um ?? '' }}</div>
                                </div>
                            @endif
                        </div>

                        <div class="table-responsive" style="min-height:30vh">
                            <table class="table table-striped table-bordered align-middle" id="job-details-table">
                                <thead>
                                    <tr>
                                        <th width="5%">Bay No.</th>
                                        <th>Rack Location</th>
                                        <th>Pallet No.</th>
                                        <th>Quantity</th>
                                        <th>U/M</th>
                                        <th width="10%">Received By</th>
                                        <th width="10%">Date Received</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($details as $row)
                                        <tr>
                                            <td>{{ $row->rsbaynum }}</td>
                                            <td>{{ $row->rsloc }}</td>
                                            <td>{{ $row->rspallet_num }}</td>
                                            <td class="text-end">{{ number_format($row->qty ?? 0, 0) }}</td>
                                            <td>{{ $row->um }}</td>
                                            <td><a href="{{ route('irms.userprofile', ['userid' => $row->rcvd_by_id]) }}">{{ $row->rcvd_by_name }}</a></td>
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