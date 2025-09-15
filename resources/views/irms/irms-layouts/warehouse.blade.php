@extends('irms.irms-partials.app')

@section('title', 'IRMS Warehouse')

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col mb-3">
                            <h1 class="d-inline-block mb-0">Warehouse</h1>
                            <!-- <button type="button" id="btnAddWarehouse" class="btn btn-success ms-3" data-bs-toggle="modal"
                                data-bs-target="#addWarehouseModal">
                                <i class="bi bi-plus-circle-fill"></i>
                                Add Warehouse
                            </button> -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <button type="button" id="btnAddWarehouse" class="btn btn-success d-flex align-items-center"
                                        data-bs-toggle="modal" data-bs-target="#addWarehouseModal">
                                        <i class="bi bi-plus-circle-fill d-none d-sm-inline me-2"></i>
                                        <span class="d-none d-sm-inline">Add Warehouse</span>
                                        <i class="bi bi-plus-circle-fill d-inline d-sm-none"></i>
                                    </button>
                                    <div class="input-group" style="max-width: 300px;">
                                        <input type="text" id="whseSearch" class="form-control"
                                            placeholder="Search warehouse...">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="warehouse-table" class="table table-striped table-bordered table-hover align-middle">

                                        <thead class="table-dark text-center">
                                            <tr>
                                                <th width="5%">Site</th>
                                                <th width="7%">Warehouse</th>
                                                <th width="10%">Description</th>
                                                <th>Address</th>
                                                <th width="5%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($warehouses as $whse)
                                                <tr>
                                                    <td class="text-center align-middle">
                                                        @if(!empty($whse->logo_pic_url))
                                                        <img src="{{ asset($whse->logo_pic_url) }}" alt="logo" class="me-1" width="40" height="40" style="object-fit:contain;vertical-align:middle;">
                                                        @endif
                                                        <!-- {{ $whse->logo_pic_url }} -->
                                                    </td>
                                                    <td>{{ $whse->rswhse }}</td>
                                                    <td>{{ $whse->name }}</td>
                                                    <td>{{ $whse->addr }}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-info btn-sm" title="Settings">
                                                            <i class="bi bi-gear-fill"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">No data found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                </table>
                                </div>
                            </div>
                            <!-- End Card Body -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Warehouse Modal -->
    <div class="modal fade" id="addWarehouseModal" tabindex="-1" aria-labelledby="addWarehouseLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('warehouse.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h1 class="modal-title fs-5" id="addWarehouseLabel">Add Warehouse</h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="rssite" class="form-label">Site</label>
                            <select name="rssite" id="rssite" class="form-select" required>
                                <option disabled selected>Select Site</option>
                                <option value="FP-SP">Fortune Packaging, Inc.</option>
                                <option value="PI-SP">Printwell, Inc.</option>
                                <option value="PIGRP">Printwell Packaging Company</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="rswhse" class="form-label">Warehouse Code</label>
                            <input type="text" class="form-control" id="rswhse" name="rswhse" maxlength="10" required>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Description</label>
                            <input type="text" class="form-control" id="name" name="name" maxlength="30" required>
                        </div>
                        <div class="mb-3">
                            <label for="addr" class="form-label">Address</label>
                            <input type="text" class="form-control" id="addr" name="addr" maxlength="60">
                        </div>
                        <!-- createdby is set automatically in backend -->
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
