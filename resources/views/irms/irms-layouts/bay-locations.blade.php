@extends('irms/irms-partials.app')
@section('title', 'IRMS Bay Locations')
@section('content')

<main class="app-main" data-user-level="{{ auth()->user()->level }}">
    <div class="app-content-wrapper">
        <div class="app-content-header">
            <div class="container-lg">
                <div class="row align-items-center">
                    <div class="col mb-3 d-flex align-items-center">
                        <h1 class="d-inline-block mb-0 me-3">Bay Locations</h1>
                        @if(session('success'))
                            <div id="alerts" class="alert alert-success py-1 px-3 mb-0" style="transition: opacity 0.7s;">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if($errors->any())
                            <div id="alerts" class="alert alert-danger py-1 px-3 mb-0" style="transition: opacity 0.7s;">{{ $errors->first() }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-lg">
                <div class="row">
                    <div class="col">
                        <div class="card mx-auto">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <button type="button" id="btnAddBay" class="btn btn-success d-flex align-items-center me-2"
                                        data-bs-toggle="modal" data-bs-target="#addBayModal">
                                        <i class="bi bi-plus-circle-fill d-none d-sm-inline me-2"></i>
                                        <span class="d-none d-sm-inline">Add Bay</span>
                                        <i class="bi bi-plus-circle-fill d-inline d-sm-none"></i>
                                    </button>
                                    <div class="input-group" style="max-width: 300px;">
                                        <input type="text" id="baylocSearch" class="form-control"
                                            placeholder="Search bay location...">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    </div>
                                </div>
                                <div class="table-responsive table-view">
                                    <table id="bayloc-table" class="table table-striped table-bordered table-hover align-middle display ">
                                        <thead>
                                            <tr>
                                                <th>Bay Number</th>
                                                <th>Warehouse</th>
                                                @if(auth()->user()->level == 1)
                                                <th width="10%">Site</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody id="baylocTableBody">
                                            
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

    <!-- View Modal -->
     <div class="modal fade" id="viewBayModal" tabindex="-1" aria-labelledby="viewBayModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">

                    <div class="modal-header bg-info text-white">
                        <h1 class="modal-title fs-5" id="viewBayModalTitle">View Bay Location</h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <table class="table table-responsive mb-0 table-borderless">
                            <tbody>
                                @if(auth()->user()->level == 1)
                                <tr>
                                    <th class="w-40">Site:</th>
                                    <td><span id="view-bay-site-desc">-</span></td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Warehouse:</th>
                                    <td><span id="view-bay-warehouse">-</span></td>
                                </tr>
                                <tr>
                                    <th>Bay Number:</th>
                                    <td><span id="view-bay-number">-</span></td>
                                </tr>
                                <tr>
                                    <th>Created Date:</th>
                                    <td><span id="view-created-date">-</span></td>
                                </tr>
                                <tr>
                                    <th>Created By:</th>
                                    <td><span id="view-created-by">-</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Close</button>
                    </div>

            </div>
        </div>
     </div>


    <!-- Add Bay Modal -->
    <div class="modal fade" id="addBayModal" tabindex="-1" aria-labelledby="addBayLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <form action="{{ route('baylocs.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h1 class="modal-title fs-5" id="addBayLabel">Add Bay</h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                <input type="hidden" id="rssite" name="rssite" value="{{ auth()->user()->rssite }}" readonly>
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
                        </div>
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                <input type="text" class="form-control" id="rsbaynum" name="rsbaynum" value="{{ old('rsbaynum') }}" required maxlength="20" placeholder="Bay Number">
                            </div>
                            @error('rsbaynum') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <input type="hidden" name="createdby" value="{{ auth()->user()->userid ?? 'system' }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <!-- <button type="submit" class="btn btn-success">Save</button> -->
                        <button type="button" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
