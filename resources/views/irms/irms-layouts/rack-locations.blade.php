@extends('irms.irms-partials.app')
@section('title', 'IRMS Rack Locations')
@section('content')
<main class="app-main">
    <div class="app-content-wrapper">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col mb-3 d-flex align-items-center">
                        <h1 class="d-inline-block mb-0 me-3">Rack Locations</h1>
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
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <!-- Tabs Navigation -->
                            <ul class="nav nav-tabs px-4 pt-3" id="rackTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="table-tab" data-bs-toggle="tab" data-bs-target="#tableTabPane" type="button" role="tab" aria-controls="tableTabPane" aria-selected="true">
                                        Table
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="map-tab" data-bs-toggle="tab" data-bs-target="#mapTabPane" type="button" role="tab" aria-controls="mapTabPane" aria-selected="false">
                                        Map
                                    </button>
                                </li>
                            </ul>
                            <!-- Tabs Content -->
                            <div class="tab-content p-4" id="rackTabsContent">
                                <div class="tab-pane fade show active" id="tableTabPane" role="tabpanel" aria-labelledby="table-tab">
                                    {{-- Existing Table Content --}}
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <button type="button" id="btnAddRack" class="btn btn-success d-flex align-items-center me-2"
                                                data-bs-toggle="modal" data-bs-target="#addRackModal">
                                            <i class="bi bi-plus-circle-fill d-none d-sm-inline me-2"></i>
                                            <span class="d-none d-sm-inline">Add Rack</span>
                                            <i class="bi bi-plus-circle-fill d-inline d-sm-none"></i>
                                        </button>

                                        <div class="input-group" style="max-width: 300px;">
                                            <input type="text" id="rackSearch" class="form-control" placeholder="Search rack location...">
                                            <span class="input-group-text">
                                                <i class="bi bi-search"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="table-responsive table-view">
                                        <table id="rackTable" class="table table-striped table-bordered table-hover align-middle display">
                                            <thead>
                                            <tr>
                                                <th>Rack Location</th>
                                                <th width="10%">Warehouse</th>
                                                <th width="10%">Bay No.</th>
                                                <!-- <th>Description</th> -->
                                                <th width="10%">Quantity</th>
                                                @if(auth()->user()->userid === 'sa')
                                                <th width="10%">Site</th>
                                                @endif
                                                <!-- <th width="10%">Create Date</th> -->
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($racklocs as $rsloc)
                                                <tr
                                                    data-rssite="{{ $rsloc->rssite }}"
                                                    data-rssite_desc="{{ $rsloc->rssite_desc }}"
                                                    data-rswhse="{{ $rsloc->rswhse }}"
                                                    data-rsbaynum="{{ $rsloc->rsbaynum }}"
                                                    data-rsloc="{{ $rsloc->rsloc }}"
                                                    data-rsdesc="{{ $rsloc->rsdesc }}"
                                                    data-qty="{{ ($rsloc->qty ?? 0) == 0 ? '0' : number_format($rsloc->qty, 0) }}"
                                                    data-create-date="{{ \Carbon\Carbon::parse($rsloc->createdate)->format('d M Y - h:i A') }}"
                                                >
                                                    <td>{{ $rsloc->rsloc }}</td>
                                                    <td>{{ $rsloc->rswhse }}</td>
                                                    <td>{{ $rsloc->rsbaynum }}</td>
                                                    <!-- <td>{{ $rsloc->rsdesc }}</td> -->
                                                    <td class="text-end me-3">{{ number_format($rsloc->qty, 0) }}</td>
                                                    @if(auth()->user()->userid === 'sa')
                                                    <td>
                                                        {{ $rsloc->rssite_desc ?? 'N/A' }}
                                                        <!-- <img src="{{ asset($rsloc->logo_pic_url) }}" alt="logo" class="mx-auto d-block" width="40" height="40" style="object-fit:contain;vertical-align:middle;"> -->
                                                    </td>
                                                    @endif
                                                    <!-- <td>{{ \Carbon\Carbon::parse($rsloc->createdate)->format('d M Y - h:i A') }}</td> -->
                                                </tr>
                                            @empty
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="mapTabPane" role="tabpanel" aria-labelledby="map-tab">
                                    <form id="rack-map-filter" class="d-flex flex-column">
                                        <div class="row">
                                            @if(auth()->user()->userid === 'sa')
                                            <div class="col-12 col-md-4 mb-2">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                                                    <select name="rssite" id="mapRsSite" class="form-select" required>
                                                        <option disabled selected>Select Site</option>
                                                        @foreach($sites as $site)
                                                            <option value="{{ $site->rssite }}">{{ $site->rssite_desc }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @else
                                            <input type="hidden" name="rssite" id="mapRsSite" value="{{ auth()->user()->rssite }}" readonly>
                                        @endif

                                        {{-- Warehouse --}}
                                        <div class="col-12 col-md-4 mb-2">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-archive"></i></span>
                                                <select id="mapRsWhse" class="form-select" name="rswhse">
                                                    <option value="">Select Warehouse</option>
                                                    @foreach($warehouses as $whse)
                                                        <option value="{{ $whse->rswhse }}" data-site="{{ $whse->rssite }}">{{ $whse->name }}</option>
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
                                                        <option value="{{ $bay->rsbaynum }}" data-site="{{ $bay->rssite }}">{{ $bay->rsbaynum }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        </div>
                                    </form>
                                
                                    <div class="d-flex justify-content-center align-items-center" style="min-height: 300px;">
                                        <div id="rack-map-grid" class="table-responsive">
                                            
                                        </div>
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
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <form action="">
                <div class="modal-header bg-primary text-white">
                    <h1 class="modal-title fs-5" id="viewRackModalLabel">View Rack Location</h1>
                    <button type="button" class="btn btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                <th>Description: </th>
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
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
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
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        @if(auth()->user()->userid === 'sa')
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
                                    @if(auth()->user()->userid === 'sa' || $whse->rssite === auth()->user()->rssite)
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
                                    @if(auth()->user()->userid === 'sa' || $bay->rssite === auth()->user()->rssite)
                                        <option value="{{ $bay->rsbaynum }}" data-site="{{ $bay->rssite }}">
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
                            <input type="text" class="form-control" id="rsloc" name="rsloc" value="{{ old('rsloc') }}" required maxlength="15" placeholder="Rack Location">
                        </div>
                        @error('rsloc') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                            <input type="text" class="form-control" id="rsdesc" name="rsdesc" value="{{ old('rsdesc') }}" maxlength="13" placeholder="Description">
                        </div>
                        @error('rsdesc') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
