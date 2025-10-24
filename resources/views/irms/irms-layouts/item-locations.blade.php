@extends('irms/irms-partials.app')

@section('title', 'IRMS Item Locations')

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
                <div class="row" id="item-list-view">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-end align-items-center mb-3">
                                    <div class="input-group" style="max-width: 300px;">
                                        <input type="text" id="itemSearch" class="form-control" placeholder="Search Item location...">
                                        <span class="input-group-text">
                                            <i class="bi bi-search"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="table-responsive table-view">
                                    <table id="itemloc-table" class="table table-striped table-hover align-middle display">
                                        <thead>
                                        <tr>
                                            <th width="10%">Job No.</th>
                                            <th>Product Item</th>
                                            <th width="10%">Quantity</th>
                                            <!-- <th width="5%">U/M</th> -->
                                            @if(auth()->user()->level == 1)
                                                <th width="10%">Site</th>
                                            @endif
                                        </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @foreach($itemlocs as $loc)
                                                <tr data-job="{{ $loc->job }}" data-rssite="{{ $loc->rssite }}"
                                                    data-item="{{ $loc->item }}" data-desc="{{ $loc->desc }}"
                                                    data-totalqty="{{ ($loc->totalqty ?? 0) == 0 ? '0' : number_format($loc->totalqty, 0) }}" data-um="{{ $loc->um }}"
                                                    data-rswhse="{{ $loc->rswhse }}">
                                                    <td>{{ $loc->job }}</td>
                                                    <td>
                                                        <div class="fw-semibold">{{ $loc->item }}</div>
                                                        <div class="small text-muted">{{ $loc->desc }}</div>
                                                    </td>
                                                    <td class="text-end">{{ ($loc->totalqty ?? 0) == 0 ? '0' : number_format($loc->totalqty, 0) }} {{ $loc->um }}</td>
                                                    @if(auth()->user()->level == 1)
                                                        <td>{{ $loc->rssite }}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="item-job-view" class="card">
                    <div class="card-header d-flex align-items-center">
                        <button type="button" id="btnBackItems" class="btn border border-secondary d-flex align-items-center">
                            <i class="bi bi-arrow-left d-sm-inline me-2"></i>
                            <span>Back</span>
                        </button>
                        <h5 class="card-title fw-bold ms-2">Item Details </h5>
                    </div>
                    <div class="card-body">
                        <div class="bg-secondary bg-opacity-50 p-2 mb-3 rounded">
                            <div class="bg-light p-3 rounded shadow-sm">
                                <div class="row mb-2">
                                    @if(auth()->user()->level == 1)
                                    <div class="col-4">
                                        <span class="fw-bold">Site:</span>
                                        <span id="item-details-site" class="ms-2"></span>
                                    </div>
                                    @endif
                                    <div class="col-6">
                                        <span class="fw-bold">Warehouse:</span>
                                        <span id="item-details-warehouse" class="ms-2"></span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4">
                                        <span class="fw-bold">Job:</span>
                                        <span id="item-details-jobco" class="ms-2"></span>
                                    </div>
                                    <div class="col-8">
                                        <span class="fw-bold">Item:</span>
                                        <span id="item-details-item" class="ms-2"></span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4">
                                        <span class="fw-bold">Total Qty:</span>
                                        <span id="item-details-total_qty" class="ms-2"></span>
                                    </div>
                                    <div class="col-4">
                                        <span class="fw-bold">U/M:</span>
                                        <span id="item-details-um" class="ms-2"></span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <span class="fw-bold">Description:</span>
                                        <span id="item-details-description" class="ms-2"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center align-items-center">
                            <table id="job-rack-list" class="table table-striped table-bordered table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Rack Location</th>
                                        <th width="10%">Pallet No.</th>
                                        <th width="10%">Quantity</th>
                                        <th width="5%">U/M</th>
                                        <th width="15%">Received By</th>
                                        <th width="15%">Date Received</th>
                                    </tr>
                                </thead>
                                <tbody id="job-details-body">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    window.csrfToken = "{{ csrf_token() }}";
</script>

@endsection
