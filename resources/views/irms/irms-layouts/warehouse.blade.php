@extends('irms.irms-partials.app')
@section('title', 'IRMS Warehouse')
@section('content')
<main class="app-main">
    <div class="app-content-wrapper">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col mb-3 d-flex align-items-center">
                        <h1 class="d-inline-block mb-0 me-3">Warehouse</h1>
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
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <button type="button" id="btnAddWarehouse" class="btn btn-success d-flex align-items-center me-2"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addWarehouseModal">
                                        <i class="bi bi-plus-circle-fill d-none d-inline d-sm-inline me-2"></i>
                                        <span class="d-none d-md-inline">Add Warehouse</span>
                                        <i class="bi bi-plus-circle-fill d-inline d-sm-none"></i>
                                    </button>
                                    <div class="input-group" style="max-width: 300px;">
                                        <input type="text" id="whseSearch" class="form-control"
                                            placeholder="Search warehouse...">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    </div>
                                </div>
                                <div class="table-responsive table-view">
                                    <table id="warehouse-table" class="table table-striped table-bordered table-hover align-middle display">
                                        <thead>
                                            <tr>
                                                <th width="10%">Warehouse</th>
                                                <th>Description</th>
                                                <!-- <th width="20%">Address</th> -->
                                                @if(auth()->user()->userid === 'sa')
                                                <th width="10%">Site</th>
                                                @endif
                                                <!-- <th width="5%">Action</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($warehouses as $whse)
                                                <tr data-rssite="{{ $whse->rssite }}"
                                                    data-rssite_desc="{{ $whse->rssite_desc}}"
                                                    data-rswhse="{{ $whse->rswhse }}"
                                                    data-name="{{ $whse->name }}"
                                                    data-addr="{{ $whse->addr }}">
                                                    <td>{{ $whse->rswhse }}</td>
                                                    <td>{{ $whse->name }}</td>
                                                    <!-- <td>{{ $whse->addr }}</td> -->
                                                    @if(auth()->user()->userid === 'sa')
                                                    <td>
                                                        {{ $whse->rssite_desc ?? 'N/A' }}
                                                        <!-- <img src="{{ asset($whse->logo_pic_url) }}" class="me-1" width="40" height="40" style="object-fit:contain;vertical-align:middle;"> -->
                                                    </td>
                                                    @endif
                                                </tr>
                                            @empty
                                            @endforelse
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

    <!-- Add Warehouse Modal -->
    <div class="modal fade" id="addWarehouseModal" tabindex="-1" aria-labelledby="addWarehouseLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-l">
            <div class="modal-content">
                <form action="{{ route('warehouse.store') }}" method="POST" enctype="multipart/form-data"   >
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h1 class="modal-title fs-5" id="addWarehouseLabel">Add Warehouse</h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close">
                        </button>
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
                                <input type="hidden" id="rssite" name="rssite" value="{{ auth()->user()->rssite }}" readonly>
                            @endif
                            @error('rssite') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                <input type="text" class="form-control" id="rswhse" name="rswhse" value="{{ old('rswhse') }}" required maxlength="10" placeholder="Warehouse Code">
                            </div>
                            @error('rswhse') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-archive"></i></span>
                                <input type="text" class="form-control" id="name" name="name" autocomplete="on" value="{{ old('name') }}" required maxlength="255" placeholder="Warehouse Name">
                            </div>
                            @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                <input type="text" class="form-control" id="addr" name="addr" value="{{ old('addr') }}" maxlength="255" placeholder="Address">
                            </div>
                            @error('addr') <div class="text-danger small">{{ $message }}</div> @enderror
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

    <!-- {{-- Edit Warehouse Modal --}} -->
    <div class="modal fade" id="editWarehouseModal" tabindex="-1" aria-labelledby="editWarehouseLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editWarehouseForm" method="POST" action="{{ route('warehouse.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-warning text-white">
                        <h1 class="modal-title fs-5" id="editWarehouseLabel">Edit Warehouse</h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit-orig-rssite" name="orig_rssite">
                        <input type="hidden" id="edit-orig-rswhse" name="orig_rswhse">
                        @if(auth()->user()->userid === 'sa')
                        <div class="mb-3">
                            <label for="edit-rssite" class="form-label">Site</label>
                            <select name="rssite" id="edit-rssite" class="form-select" required>
                                <option selected disabled>Select Site</option>
                                @foreach($sites as $site)
                                    <option value="{{ $site->rssite }}">{{ $site->rssite_desc }}</option>
                                @endforeach
                            </select>
                        </div>
                        @else
                            <input type="hidden" id="rssite" name="rssite" value="{{ auth()->user()->rssite }}" readonly>
                        @endif
                        <div class="mb-3">
                            <label for="edit-rswhse" class="form-label">Warehouse Code</label>
                            <input type="text" class="form-control" id="edit-rswhse" name="rswhse" value="" maxlength="10" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-name" class="form-label">Description</label>
                            <input type="text" class="form-control" autocomplete="on" id="edit-name" name="name" maxlength="30" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-addr" class="form-label">Address</label>
                            <input type="text" class="form-control" id="edit-addr" name="addr" maxlength="60">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Responsive Warehouse View Modal -->
    <div class="modal fade" id="viewWarehouseModal" tabindex="-1" aria-labelledby="viewWarehouseLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h1 class="modal-title fs-5" id="viewWarehouseLabel">
                        <span id="view-warehouse-label-name">-</span>
                    </h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="w-40">Site:</th>
                                <td><span id="view-warehouse-site-desc">-</span></td>
                            </tr>
                            <tr>
                                <th>Warehouse Code:</th>
                                <td><span id="view-warehouse-code">-</span></td>
                            </tr>
                            <tr>
                                <th>Description:</th>
                                <td><span id="view-warehouse-name">-</span></td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td><span id="view-warehouse-addr">-</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer flex-wrap justify-content-between">
                    @if (Route::has('warehouse.destroy'))
                    <button type="button" class="btn btn-danger" id="btnDeleteWarehouse">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                    @endif
                    <button type="button" class="btn btn-warning"
                            data-bs-target="#editWarehouseModal"
                            data-bs-toggle="modal"
                            id="editWarehouseBtn">
                        <i class="bi bi-pencil-square"></i> Edit
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
