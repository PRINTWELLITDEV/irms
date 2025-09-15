@extends('irms.irms-partials.app')
@section('title', 'IRMS Dashboard')
@section('content')
<div class="wrapper">
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col mb-3">
                        <h1 class="d-inline-block mb-0">Manage Users</h1>
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
                                <button type="button" id="btnAddUser" class="btn btn-success d-flex align-items-center"
                                    data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    <i class="bi bi-person-plus-fill d-none d-sm-inline me-2"></i>
                                    <span class="d-none d-sm-inline">Add User</span>
                                    <i class="bi bi-person-plus-fill d-inline d-sm-none"></i>
                                </button>

                                <div class="input-group" style="max-width: 300px;">
                                    <input type="text" id="userSearch" class="form-control" placeholder="Search users...">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="users-table" class="table table-striped table-bordered table-hover align-middle">
                                    <thead class="table-dark text-center">
                                        <tr class="">
                                            <th width="5%">Profile</th>
                                            <th width="10%">User ID</th>
                                            <th width="15%">Name</th>
                                            <th width="20%">Email</th>
                                            <th width="5%">Site</th>
                                            <th width="5%">Level</th>
                                            <th width="5%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $user)
                                            <tr>
                                                <td class="text-center align-middle">
                                                    <img src="{{ $user->profile_pic_url ? asset($user->profile_pic_url) : asset('uploads/user-profile/noprofile.png') }}"
                                                        alt="profile" class="rounded-circle" width="50" height="50">
                                                </td>
                                                <td class="align-middle">{{ $user->userid }}</td>
                                                <td class="align-middle">{{ $user->name }}</td>
                                                <td class="align-middle">{{ $user->email }}</td>
                                                <td class="align-middle">
                                                    @if(!empty($user->logo_pic_url))
                                                        <img src="{{ asset($user->logo_pic_url) }}" alt="logo" class="me-1" width="50" height="50" style="object-fit:contain;vertical-align:middle;">
                                                    @endif
                                                    <!-- {{ $user->rssite_desc ?? $user->rssite }} -->
                                                </td>
                                                <td class="align-middle">{{ $user->level }}</td>
                                                <td class="align-middle text-center">
                                                    <button type="button" class="btn btn-sm btn-secondary btn-settings"
                                                            data-userid="{{ $user->userid }}" title="Settings">
                                                        <i class="bi bi-gear-fill"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">No data available</td>
                                            </tr>
                                        @endforelse
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

<!-- Add User Modal (structure same as warehouse modal) -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('RsUserController.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h1 class="modal-title fs-5" id="addUserLabel">Add User</h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="profile_pic_url" class="form-label">Profile Picture</label>
                        <input type="file" name="profile_pic_url" id="profile_pic_url" class="form-control">
                        @error('profile_pic_url') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="rssite" class="form-label">Site</label>
                        <select name="rssite" id="rssite" class="form-select" required>
                            <option disabled selected>Select Site</option>
                            <option value="FP-SP" {{ old('rssite') == 'FP-SP' ? 'selected' : '' }}>Fortune Packaging, Inc.</option>
                            <option value="PI-SP" {{ old('rssite') == 'PI-SP' ? 'selected' : '' }}>Printwell, Inc.</option>
                            <option value="PIGRP" {{ old('rssite') == 'PIGRP' ? 'selected' : '' }}>Printwell Packaging Company</option>
                        </select>
                        @error('rssite') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="userid" class="form-label">User ID</label>
                        <input type="text" class="form-control" id="userid" name="userid" value="{{ old('userid') }}" required maxlength="8">
                        @error('userid') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" maxlength="255">
                        @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" maxlength="255" required>
                        @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select name="gender" id="gender" class="form-select">
                            <option disabled selected>Select a gender</option>
                            <option value="male" {{ old('gender')=='male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender')=='female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required maxlength="255">
                        @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
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

<!-- Settings Modal (sibling to add modal) -->
<div class="modal fade" id="settingsModals" tabindex="-1" aria-labelledby="label_settings" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="label_settings">Settings</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- populate dynamically if needed -->
                <div id="settings-modal-content">Select a user to view settings.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
