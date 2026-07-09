@extends('irms/irms-partials.app')

@section('title', 'Quantity Move')

@section('content')

    <main class="app-main">
        <div class="app-content-wrapper">
            <div class="app-content-header">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col mb-3 d-flex align-items-center">
                            <h1 class="d-inline-block mb-0 me-3">Warehouse Goods Dispatching</h1>
                            @if(session('success'))
                                <div id="success-alert" class="alert alert-success py-1 px-3 mb-0"
                                    style="transition: opacity 0.7s;">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if($errors->any())
                                <div id="error-alert" class="alert alert-danger py-1 px-3 mb-0"
                                    style="transition: opacity 0.7s;">{{ $errors->first() }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    <div class="row justify-content-md-center">
                        <div class="col-12 col-lg-10">
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header bg-warning text-white d-flex align-items-center">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    <h5 class="mb-0">Quantity Move Form</h5>
                                </div>
                                <div class="card-body p-4">
                                    @php
                                        $isSa = auth()->user()->level == 1;
                                    @endphp

                                    <form id="quantityMoveForm" method="POST" action="#">
                                        @csrf

                                        @if($isSa)
                                            <div class="row mb-4">
                                                <div class="col-12">
                                                    <div class="form-floating">
                                                        <select name="rssite" id="QuantityMoveRssite" class="form-select" required>
                                                            <option value="" disabled selected>Choose a site...</option>
                                                            @foreach($sites as $site)
                                                                <option value="{{ $site->rssite }}" {{ old('rssite') == $site->rssite ? 'selected' : '' }}>
                                                                    {{ $site->rssite_desc }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <label for="rssite">
                                                            <i class="bi bi-building text-danger me-1"></i>
                                                            Site <span class="text-danger">*</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <input type="hidden" name="rssite" id="QuantityMoveRssite" value="{{ auth()->user()->rssite }}">
                                        @endif

                                        <div class="col-md-12">
                                            <div class="row g-3 mb-4 d-flex flex-column justify-content-center align-items-center">

                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <select name="fromWhse" id="fromWhse" class="form-select" required>
                                                            <option value="" disabled selected>Select Warehouse First</option>
                                                            @foreach($warehouses as $warehouse)
                                                                @if(auth()->user()->level == 1 || $warehouse->rssite === auth()->user()->rssite)
                                                                    <option value="{{ $warehouse->rswhse }}" data-site="{{ $warehouse->rssite }}">
                                                                        {{ $warehouse->rswhse }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        <label for="fromWhse">
                                                            <i class="bi bi-geo-alt text-primary me-1"></i> Warehouse <span class="text-danger">*</span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="fromBayLoc" class="form-label fw-semibold text-muted">Bay Location</label>
                                                    <select name="FromBayLocation" id="fromBayLoc" class="form-select" disabled>
                                                        <option value="" selected disabled>Select Bay Location</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="fromRsloc" class="form-label fw-semibold text-muted">Rack Location</label>
                                                    <select name="FromRackLocation" id="fromRsloc" class="form-select" disabled>
                                                        <option value="" selected disabled>Select Rack Location</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="fromPosition" class="form-label fw-semibold text-muted">Position / Grid</label>
                                                    <select name="FromPosition" id="fromPosition" class="form-select" disabled>
                                                        <option value="" selected disabled>Select Position</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="formStatus" class="mt-3 text-center" style="display: none;">
                                            <div class="spinner-border spinner-border-sm text-danger me-2" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <small class="text-muted">Processing your request...</small>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="dispatching-details" class="card mb-3 shadow-sm">
                        <div class="card-header bg-warning text-dark d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                <h5 class="mb-0">Dispatching Details</h5>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <button type="button" id="btnBackDispatching" class="btn btn-outline-secondary d-flex align-items-center mb-3">
                                <i class="bi bi-arrow-left me-2"></i>
                                <span class="fw-semibold">Back to Form</span>
                            </button>

                            <div class="row g-3 mb-4">
                                <div class="col-lg-6">
                                    <div class="summary-card">
                                        <div class="summary-card-header">
                                            <i class="bi bi-info-circle me-2"></i>
                                            <h6 class="mb-0">Basic Information</h6>
                                        </div>
                                        <div class="summary-card-body">
                                            @if(auth()->user()->level == 1)
                                                <div class="summary-item">
                                                    <span class="summary-label">Site</span>
                                                    <span id="details-site" class="summary-value"></span>
                                                </div>
                                            @endif
                                            <div class="summary-item">
                                                <span class="summary-label">Date</span>
                                                <span id="details-date" class="summary-value"></span>
                                            </div>
                                            <div class="summary-item">
                                                <span class="summary-label">Warehouse</span>
                                                <span id="details-warehouse" class="summary-value"></span>
                                            </div>
                                            <div class="summary-item">
                                                <span class="summary-label">Document No.</span>
                                                <span id="details-docno" class="summary-value text-muted"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="summary-card">
                                        <div class="summary-card-header">
                                            <i class="bi bi-box me-2"></i>
                                            <h6 class="mb-0">Item & Stock Details</h6>
                                        </div>
                                        <div class="summary-card-body">
                                            <div class="summary-item">
                                                <span class="summary-label">Job / CO</span>
                                                <span id="details-jobco" class="summary-value fw-bold text-danger"></span>
                                            </div>
                                            <div class="summary-item">
                                                <span class="summary-label">Lot</span>
                                                <span id="details-lot" class="summary-value"></span>
                                            </div>
                                            <div class="summary-item">
                                                <span class="summary-label">Item Code</span>
                                                <span id="details-item" class="summary-value"></span>
                                            </div>
                                            <div class="summary-item">
                                                <span class="summary-label">Description</span>
                                                <span id="details-desc" class="summary-value text-truncate" title=""></span>
                                            </div>
                                            <div class="summary-item">
                                                <span class="summary-label">Qty on Hand</span>
                                                <div>
                                                    <span id="details-qty-on-hand" class="summary-value fw-bold text-danger"></span>
                                                    <span id="details-um" class="summary-value-unit"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <button type="button" id="btnD_SelectAll" class="btn btn-outline-warning d-flex align-items-center">
                                    <i class="bi bi-check-circle d-none d-inline d-sm-inline me-2"></i>
                                    <span class="d-none d-md-inline">Select All</span>
                                    <i class="bi bi-check-circle d-inline d-sm-none"></i>
                                </button>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-dark btn-sm ms-2" id="btnRefreshLocations">
                                        <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                                    </button>
                                    <button type="button" id="btnDispatch" class="btn btn-warning">
                                        <i class="bi bi-box-arrow-right me-2"></i> Dispatch
                                    </button>
                                </div>
                            </div>

                            <div class="table-card">
                                <div class="table-card-header">
                                    <h6 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Available Item Locations</h6>
                                    <div class="row align-items-center">
                                        <div class="col-sm-6">
                                            <small class="text-muted"><span id="selectedCount">0</span> location(s) selected</small>
                                        </div>
                                        <div class="col-sm-6 text-sm-end">
                                            <small class="text-muted">Total Qty: <span id="totalQty" class="fw-bold">0</span></small>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table id="dispatchingTable" class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="8%" class="text-center"><i class="bi bi-check-square"></i></th>
                                                <th width="20%">Rack Location</th>
                                                <th width="15%">Pallet Tag No.</th>
                                                <th width="12%">Qty Available</th>
                                                <th width="15%">Qty to Dispatch</th>
                                                <th width="8%">U/M</th>
                                                <th width="17%">Date Dispatched</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

@endsection