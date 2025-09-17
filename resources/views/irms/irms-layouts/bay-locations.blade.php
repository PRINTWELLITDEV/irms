@extends('irms/irms-partials.app')

@section('title', 'IRMS Bay Locations')

@section('content')

    <div class="wrapper">
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col mb-3 d-flex align-items-center">
                            <h1 class="d-inline-block mb-0">Bay Locations</h1>
                            @if(session('success'))
                                <div id="success-alert" class="alert alert-success py-1 px-3 mb-0" style="transition: opacity 0.7s;">
                                    {{ session('success') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="row">
                    <div class="col">
                        <div class="card mx-auto">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <button type="button" id="btnAddBay" class="btn btn-success d-flex align-items-center"
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
                                <div class="table-responsive">
                                    <table id="bayloc-table" class="table table-striped table-bordered table-hover align-middle">
                                        <thead class="table-dark text-center">
                                            <tr>
                                                <th width="5%">Site</th>
                                                <th>Bay Number</th>
                                                <th>Created Date</th>
                                                <th width="10%">Created By</th>
                                                <!-- <th width="8%">Action</th>  -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($baylocs as $bay)
                                                <tr>
                                                    <td class="text-center align-middle">
                                                        @if(!empty($bay->logo_pic_url))
                                                            <img src="{{ asset($bay->logo_pic_url) }}" alt="logo" class="mx-auto d-block" width="40" height="40" style="object-fit:contain;vertical-align:middle;">
                                                        @endif
                                                    </td>
                                                    <td>{{ $bay->rsbaynum }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($bay->createdate)->format('d-M-Y h:i A') }}</td>
                                                    <td>{{ $bay->name }}</td>
                                                    <!-- <td class="align-middle text-center">
                                                        <button type="button" class="btn btn-sm btn-secondary btn-settings"
                                                                data-baynum="{{ $bay->rsbaynum }}" title="Settings">
                                                            <i class="bi bi-gear-fill"></i>
                                                        </button>
                                                    </td> -->
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">No bay locations found</td>
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
        </div>
    </div>

        <!-- Add Bay Modal -->
    <div class="modal fade" id="addBayModal" tabindex="-1" aria-labelledby="addBayLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-l">
            <div class="modal-content">
                <form action="{{ route('baylocs.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h1 class="modal-title fs-5" id="addBayLabel">Add Bay</h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        
                        <div class="mb-3">
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
                            @error('rssite') <div class="text-danger small">{{ $message }}</div> @enderror
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
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
