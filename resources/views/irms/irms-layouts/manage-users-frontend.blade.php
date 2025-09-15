@extends('irms.irms-partials.app')
@section('title', 'Manage Users - Frontend Demo')

@section('content')
<div class="wrapper">
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col mb-3">
                        <h1 class="d-inline-block mb-0">Manage Users (Frontend Demo)</h1>
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
                                <button type="button" id="btnAddUser" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    <i class="bi bi-person-plus-fill"></i> Add User
                                </button>

                                <div class="input-group" style="max-width:300px;">
                                    <input type="text" id="userSearch" class="form-control" placeholder="Search users...">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="users-table" class="table table-striped table-bordered table-hover align-middle text-center">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Profile</th>
                                            <th>User ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Site</th>
                                            <th>Level</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Static demo rows -->
                                        <tr>
                                            <td class="align-middle">
                                                <img src="https://via.placeholder.com/50" alt="profile" class="rounded-circle" width="50" height="50">
                                            </td>
                                            <td class="align-middle">PPC1190</td>
                                            <td class="align-middle">Mico Limbanganon</td>
                                            <td class="align-middle">mico.limbanganon@printwell.com.ph</td>
                                            <td class="align-middle">Printwell, Inc.</td>
                                            <td class="align-middle">Admin</td>
                                            <td class="align-middle text-center">
                                                <button type="button" class="btn btn-sm btn-secondary btn-settings"
                                                        data-userid="PPC1190" data-name="Mico Limbanganon" title="Settings">
                                                    <i class="bi bi-gear-fill"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="align-middle">
                                                <img src="https://via.placeholder.com/50" alt="profile" class="rounded-circle" width="50" height="50">
                                            </td>
                                            <td class="align-middle">JDOE01</td>
                                            <td class="align-middle">John Doe</td>
                                            <td class="align-middle">john.doe@example.com</td>
                                            <td class="align-middle">Fortune Packaging</td>
                                            <td class="align-middle">User</td>
                                            <td class="align-middle text-center">
                                                <button type="button" class="btn btn-sm btn-secondary btn-settings"
                                                        data-userid="JDOE01" data-name="John Doe" title="Settings">
                                                    <i class="bi bi-gear-fill"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="align-middle">
                                                <img src="https://via.placeholder.com/50" alt="profile" class="rounded-circle" width="50" height="50">
                                            </td>
                                            <td class="align-middle">ASMI01</td>
                                            <td class="align-middle">Asha Smith</td>
                                            <td class="align-middle">asha.smith@example.com</td>
                                            <td class="align-middle">Printwell Packaging</td>
                                            <td class="align-middle">Manager</td>
                                            <td class="align-middle text-center">
                                                <button type="button" class="btn btn-sm btn-secondary btn-settings"
                                                        data-userid="ASMI01" data-name="Asha Smith" title="Settings">
                                                    <i class="bi bi-gear-fill"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div> <!-- /.card-body -->
                    </div> <!-- /.card -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal (frontend only) -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form onsubmit="event.preventDefault(); alert('This is a frontend demo — no backend call.');">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="addUserLabel">Add User (Demo)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Site</label>
                        <select class="form-select">
                            <option>FP-SP</option>
                            <option>PI-SP</option>
                            <option>PIGRP</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">User ID</label>
                        <input class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save (Demo)</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Settings Modal (frontend) -->
<div class="modal fade" id="settingsModals" tabindex="-1" aria-labelledby="label_settings" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="settings-modal-content">Select a user to view settings.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- DataTables + Bootstrap CDN (frontend-only) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // init DataTable
    var table = $('#users-table').DataTable({
        paging: true,
        info: true,
        lengthChange: false,
        pageLength: 10,
        language: { emptyTable: "No data available" }
    });

    // external search
    document.getElementById('userSearch')?.addEventListener('keyup', function () {
        table.search(this.value).draw();
    });

    // settings buttons: populate modal content and show
    document.querySelectorAll('.btn-settings').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var userid = this.dataset.userid || '';
            var name = this.dataset.name || '';
            var content = document.getElementById('settings-modal-content');
            content.innerHTML = `
                <p><strong>User ID:</strong> ${userid}</p>
                <p><strong>Name:</strong> ${name}</p>
                <p>This is a frontend demo. Replace with AJAX to load real settings.</p>
            `;
            var modal = new bootstrap.Modal(document.getElementById('settingsModals'));
            modal.show();
        });
    });
});
</script>
@endsection
