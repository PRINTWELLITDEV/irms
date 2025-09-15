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
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <button type="button" id="btnAddWarehouse" class="btn btn-success" data-bs-toggle="modal"
                                        data-bs-target="#addWarehouseModal">
                                        <i class="bi bi-plus-circle-fill"></i>
                                        Add Warehouse
                                    </button>
                                    <div class="input-group" style="max-width: 300px;">
                                        <input type="text" id="whseSearch" class="form-control"
                                            placeholder="Search warehouse...">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    </div>
                                </div>
                                <table id="warehouse-table" class="table table-striped table-bordered table-hover align-middle">
                                    <thead class="table-dark text-center">
                                        <tr>
                                            <th>Site</th>
                                            <th>Warehouse</th>
                                            <th>Description</th>
                                            <th>Address</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($warehouses as $whse)
                                            <tr>
                                                <td>{{ $whse->rssite }}</td>
                                                <td>{{ $whse->rswhse }}</td>
                                                <td>{{ $whse->name }}</td>
                                                <td>{{ $whse->addr }}</td>
                                                <td class="text-center">
                                                    {{-- The corrected button with Bootstrap attributes and data-whse-id --}}
                                                    <button type="button" class="btn btn-secondary btn-sm btn-settings"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#warehouseSettingsModal"
                                                        title="Settings">
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
                                @foreach($sites as $site)
                                    <option value="{{ $site->rssite }}">{{ $site->rssite_desc }}</option>
                                @endforeach
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Warehouse Settings Modal -->
    <div class="modal fade" id="warehouseSettingsModal" tabindex="-1" aria-labelledby="warehouseSettingsLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="warehouseSettingsLabel">Warehouse Settings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Site</dt>
                        <dd class="col-sm-8" id="ws-site"></dd>

                        <dt class="col-sm-4">Warehouse</dt>
                        <dd class="col-sm-8" id="ws-whse"></dd>

                        <dt class="col-sm-4">Description</dt>
                        <dd class="col-sm-8" id="ws-name"></dd>

                        <dt class="col-sm-4">Address</dt>
                        <dd class="col-sm-8" id="ws-addr"></dd>
                    </dl>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-warning" data-bs-target="#editWarehouseModal" data-bs-toggle="modal">Edit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- {{-- Edit Warehouse Modal --}} -->
    <div class="modal fade" id="editWarehouseModal" tabindex="-1" aria-labelledby="editWarehouseLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editWarehouseForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-warning text-white">
                        <h1 class="modal-title fs-5" id="editWarehouseLabel">Edit Warehouse</h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- <input type="hidden" id="edit-whse-id" name="id"> --}}
                        <div class="mb-3">
                            <label for="edit-rssite" class="form-label">Site</label>
                            <select name="rssite" id="edit-rssite" class="form-select" required>
                                <option selected disabled>Select Site</option>
                                @foreach($sites as $site)
                                    <option value="{{ $site->rssite }}">{{ $site->rssite_desc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit-rswhse" class="form-label">Warehouse Code</label>
                            <input type="text" class="form-control" id="edit-rswhse" name="rswhse" value="" maxlength="10" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-name" class="form-label">Description</label>
                            <input type="text" class="form-control" id="edit-name" name="name" maxlength="30" required>
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


    <script>
        // Warehouse settings and edit modal functionality

        // document.addEventListener("DOMContentLoaded", function () {
        //     // Listener for the main table's settings buttons
        //     document.querySelectorAll(".btn-settings").forEach(function (btn) {
        //         btn.addEventListener("click", function () {
        //             const site = this.dataset.rssite || "";
        //             const whse = this.dataset.rswhse || "";
        //             const name = this.dataset.name || "";
        //             const addr = this.dataset.addr || "";

        //             // Populate the settings modal
        //             document.getElementById("ws-site").textContent = site;
        //             document.getElementById("ws-whse").textContent = whse;
        //             document.getElementById("ws-name").textContent = name;
        //             document.getElementById("ws-addr").textContent = addr;

        //             // Set data attributes on the edit and delete buttons in the settings modal
        //             const editBtn = document.querySelector("#warehouseSettingsModal .btn-warning");
        //             editBtn.dataset.rssite = site;
        //             editBtn.dataset.rswhse = whse;
        //             editBtn.dataset.name = name;
        //             editBtn.dataset.addr = addr;
        //         });
        //     });

        //     // Listener for the "Edit" button inside the settings modal
        //     document.querySelector("#warehouseSettingsModal .btn-warning").addEventListener("click", function() {
        //         // Get data from the clicked edit button
        //         const whseId = this.dataset.whseId;
        //         const site = this.dataset.rssite;
        //         const whse = this.dataset.rswhse;
        //         const name = this.dataset.name;
        //         const addr = this.dataset.addr;

        //         // Populate the edit modal form fields
        //         document.getElementById("edit-rssite").value = site;
        //         document.getElementById("edit-rswhse").value = whse;
        //         document.getElementById("edit-name").value = name;
        //         document.getElementById("edit-addr").value = addr;

        //         // Update the form action URL dynamically
        //         const editForm = document.getElementById("editWarehouseForm");
        //         // Assuming your update route looks something like '/warehouse/123'
        //         editForm.action = `/warehouse/${whseId}`;
        //     });
        // });
    </script>
@endsection
