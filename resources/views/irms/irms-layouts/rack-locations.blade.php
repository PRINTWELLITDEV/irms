@extends('irms.irms-partials.app')
@section('title', 'IRMS Rack Locations')
@section('content')
    <main class="app-main" data-user-level="{{ auth()->user()->level }}">
        <div class="app-content-wrapper">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col mb-3 d-flex align-items-center">
                            <h1 class="d-inline-block mb-0 me-3">Rack Locations</h1>
                            @if(session('success'))
                                <div id="success-alert" class="alert alert-success py-1 px-3 mb-0"
                                    style="transition: opacity 0.7s;">
                                    {{ session('success') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <!-- Tabs Navigation -->
                                <ul class="nav nav-tabs px-4 pt-3" id="rackTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="map-tab" data-bs-toggle="tab"
                                            data-bs-target="#mapTabPane" type="button" role="tab" aria-controls="mapTabPane"
                                            aria-selected="false">
                                            Map
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="table-tab" data-bs-toggle="tab"
                                            data-bs-target="#tableTabPane" type="button" role="tab"
                                            aria-controls="tableTabPane" aria-selected="true">
                                            Table
                                        </button>
                                    </li>
                                </ul>
                                <!-- Tabs Content -->
                                <div class="tab-content p-4" id="rackTabsContent">
                                    <div class="tab-pane fade show active" id="tableTabPane" role="tabpanel"
                                        aria-labelledby="table-tab">
                                        {{-- Existing Table Content --}}
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <button type="button" id="btnAddRack"
                                                class="btn btn-success d-flex align-items-center me-2"
                                                data-bs-toggle="modal" data-bs-target="#addRackModal">
                                                <i class="bi bi-plus-circle-fill d-none d-sm-inline me-2"></i>
                                                <span class="d-none d-sm-inline">Add Rack</span>
                                                <i class="bi bi-plus-circle-fill d-inline d-sm-none"></i>
                                            </button>

                                            <div class="input-group" style="max-width: 300px;">
                                                <input type="text" id="rackSearch" class="form-control"
                                                    placeholder="Search rack location...">
                                                <span class="input-group-text">
                                                    <i class="bi bi-search"></i>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="table-responsive table-view">
                                            <table id="rackTable"
                                                class="table table-striped table-bordered table-hover align-middle display">
                                                <thead>
                                                    <tr>
                                                        <th width="20%">Rack Location</th>
                                                        <th width="10%">Warehouse</th>
                                                        <th width="10%">Bay No.</th>
                                                        <!-- <th>Description</th> -->
                                                        <th width="10%">Quantity</th>
                                                        <th width="10%">Quarantine</th>
                                                        @if(auth()->user()->level == 1)
                                                            <th width="10%">Site</th>
                                                        @endif
                                                        <!-- <th width="10%">Create Date</th> -->
                                                    </tr>
                                                </thead>
                                                <tbody id="rackTableBody">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="mapTabPane" role="tabpanel" aria-labelledby="map-tab">
                                        <form id="rack-map-filter" class="d-flex flex-column">
                                            <div class="row">
                                                @if(auth()->user()->level == 1)
                                                    <div class="col-12 col-md-4 mb-2">
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="bi bi-building"></i></span>
                                                            <select name="rssite" id="mapRsSite" class="form-select" required>
                                                                <option disabled selected>Select Site</option>
                                                                @foreach($sites as $site)
                                                                    <option value="{{ $site->rssite }}">{{ $site->rssite_desc }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                @else
                                                    <input type="hidden" name="rssite" id="mapRsSite"
                                                        value="{{ auth()->user()->rssite }}" readonly>
                                                @endif

                                                {{-- Warehouse --}}
                                                <div class="col-12 col-md-4 mb-2">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bi bi-archive"></i></span>
                                                        <select id="mapRsWhse" class="form-select" name="rswhse">
                                                            <option value="">Select Warehouse</option>
                                                            @foreach($warehouses as $whse)
                                                                <option value="{{ $whse->rswhse }}"
                                                                    data-site="{{ $whse->rssite }}">{{ $whse->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                {{-- Bay --}}
                                                <div class="col-12 col-md-4 mb-2">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bi bi-box-seam"></i></span>
                                                        <select id="mapRsBay" class="form-select" name="rsbaynum">
                                                            <option value="">Select Bay</option>
                                                            @foreach($baynums as $bay)
                                                                <option value="{{ $bay->rsbaynum }}"
                                                                    data-site="{{ $bay->rssite }}"
                                                                    data-whse="{{ $bay->rswhse }}">
                                                                    {{ $bay->rsbaynum }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div id="rackMapLegend" class="d-flex align-items-center gap-3 d-none">
                                                <small class="d-flex align-items-center">
                                                    <span class="badge me-1" style="background:linear-gradient(180deg, rgb(231, 141, 141) 0%, rgb(184, 12, 12) 100%, rgb(124, 13, 13) 100%);">&nbsp;</span>
                                                    <b>Quarantined</b>
                                                </small>
                                                <small class="d-flex align-items-center">
                                                    <span class="badge me-1" style="background:linear-gradient(180deg, rgb(247, 255, 247) 0%, rgb(203, 255, 203) 100%);">&nbsp;</span>
                                                    <b>Empty Rack</b>
                                                </small>
                                                <!--<small class="d-flex align-items-center" >
                                                    <span class="badge me-1" style="background:linear-gradient(180deg, rgb(232, 255, 230) 0%, rgb(143, 224, 143) 100%, rgb(7, 196, 1) 100%);">&nbsp;</span>
                                                    Lightly Occupied
                                                </small>
                                                <small class="d-flex align-items-center" >
                                                    <span class="badge me-1" style="background:linear-gradient(180deg, rgb(181, 253, 181) 0%, rgb(103, 214, 103) 100%, rgb(15, 173, 1) 100%);">&nbsp;</span>
                                                    Partially Occupied
                                                </small>
                                                <small class="d-flex align-items-center" >
                                                    <span class="badge me-1" style="background:linear-gradient(180deg, rgb(171, 241, 162) 0%, rgb(64, 182, 64) 100%, rgb(15, 158, 2) 100%);">&nbsp;</span>
                                                    Heavily Occupied
                                                </small>
                                                <small class="d-flex align-items-center" >
                                                    <span class="badge me-1" style="background:linear-gradient(180deg, rgb(169, 238, 152) 0%, rgb(33, 148, 33) 100%, rgb(9, 139, 5) 100%);">&nbsp;</span>
                                                    Very Heavily Occupied
                                                </small>-->
                                                <small class="d-flex align-items-center">
                                                    <span class="badge me-1" style="background:linear-gradient(180deg, rgb(146, 233, 134) 0%, rgb(13, 116, 13) 100%, rgb(2, 110, 13) 100%)">&nbsp;</span>
                                                    <b>Full Rack</b>
                                                </small>
                                                <small class="d-flex align-items-center">
                                                    <span class="badge me-1" style="background:linear-gradient(180deg,  rgb(255,245,200) 0%, rgb(255, 208, 65) 100%, rgb(255, 230, 7) 100%)">&nbsp;</span>
                                                    <b>WIP</b>
                                                </small>
                                                <small class="d-flex align-items-center">
                                                    <span class="badge me-1" style="background:linear-gradient(180deg, rgb(255, 198, 151) 0%, rgb(248, 147, 64) 100%, rgb(187, 84, 0) 100%)"">&nbsp;</span>
                                                    <b>For Stickering</b>
                                                </small>
                                                
                                            </div>

                                            <button type="button" id="btnRackMapSort" class="btn btn-outline-dark btn-sm d-none">
                                                <i class="bi bi-arrow-left-right me-1"></i>
                                                <span id="rackMapSortText">Sort: Left to Right</span>
                                            </button>
                                        </div>
                                        <div class="d-flex justify-content-center align-items-center"
                                            style="min-height: 300px;">
                                            <div id="rack-map-grid" class="table-responsive"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- View Modals -->
    <div class="modal fade" id="viewRackModal" tabindex="-1" aria-labelledby="viewRackModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="" id="viewRack">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h1 class="modal-title fs-5" id="viewRackModalLabel">View Rack Location: <span id="title-rack-location">-</span></h1>
                        <button type="button" class="btn btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-responsive mb-0 table-borderless" id="viewRackModals">
                            <tbody>
                                <tr>
                                    <th>Warehouse: </th>
                                    <td>
                                        <span id="view-rack-warehouse">-</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Bay No.: </th>
                                    <td>
                                        <span class="text-end" id="view-rack-baynum">-</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Location: </th>
                                    <td>
                                        <span id="view-rack-location">-</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Rack Description: </th>
                                    <td>
                                        <span id="view-rack-description">-</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Quantity: </th>
                                    <td>
                                        <span id="view-rack-quantity">-</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Create Date: </th>
                                    <td>
                                        <span id="view-rack-createDate">-</span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Quarantine: </th>
                                        <td>
                                            <input type="checkbox" id="modal_is_quarantine" class="modal-checkbox">
                                        </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-body">
                        <h5 class="modal-title">Items in this Rack Location</h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Job No.</th>
                                        <th>Product Item</th>
                                        <th>Quantity</th>
                                        <th>Pallet No.</th>
                                    </tr>
                                </thead>
                                <tbody id="view-rack-items-body">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="saveBtn" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Rack Location Modal -->
    <div class="modal fade" id="addRackModal" tabindex="-1" aria-labelledby="addRackModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <form action="{{ route('racklocations.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h1 class="modal-title fs-5" id="addRackModalLabel">Add Rack Location</h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            @if(auth()->user()->level == 1)
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                                    <select name="rssite" id="rssite" class="form-select" required>
                                        <option disabled selected>Select Site</option>
                                        @foreach($sites as $site)
                                            <option value="{{ $site->rssite }}" {{ old('rssite') == $site->rssite ? 'selected' : '' }}>
                                                {{ $site->rssite_desc }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <input type="hidden" name="rssite" id="rssite" value="{{ auth()->user()->rssite }}" readonly>
                            @endif
                            @error('rssite') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-archive"></i></span>
                                <select name="rswhse" id="rswhse" class="form-select" required>
                                    <option disabled selected>Select Warehouse</option>
                                    @foreach($warehouses as $whse)
                                        @if(auth()->user()->level == 1 || $whse->rssite === auth()->user()->rssite)
                                            <option value="{{ $whse->rswhse }}" data-site="{{ $whse->rssite }}">
                                                {{ $whse->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            @error('rswhse') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                <select name="rsbaynum" id="rsbaynum" class="form-select" required>
                                    <option disabled selected>Select Bay Number</option>
                                    @foreach($baynums as $bay)
                                        @if(auth()->user()->level == 1 || $bay->rssite === auth()->user()->rssite)
                                            <option value="{{ $bay->rsbaynum }}" 
                                                    data-site="{{ $bay->rssite }}" 
                                                    data-whse="{{ $bay->rswhse }}">
                                                {{ $bay->rsbaynum }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            @error('rsbaynum') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-box"></i></span>
                                <input type="text" class="form-control" id="rsloc" name="rsloc" value="{{ old('rsloc') }}"
                                    required maxlength="15" placeholder="Rack Location">
                            </div>
                            @error('rsloc') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                <input type="text" class="form-control" id="rsdesc" name="rsdesc"
                                    value="{{ old('rsdesc') }}" maxlength="13" placeholder="Description">
                            </div>
                            @error('rsdesc') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <!--<label class="form-label d-block">Quarantine</label>-->
                                    
   
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <!-- <button type="submit" class="btn btn-primary">Save</button> -->
                        <button type="button" class="btn btn-primary" id="btnSaveRack">Save</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection