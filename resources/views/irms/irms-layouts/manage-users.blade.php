@extends('irms.irms-partials.app')
@section('title', 'IRMS Manage Users')
@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col mb-3 d-flex align-items-center">
                            <h1 class="d-inline-block mb-0">Manage Users</h1>
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
                                        <input type="text" id="userSearch" class="form-control"
                                            placeholder="Search users...">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table id="users-table"
                                        class="table table-responsive table-striped table-bordered table-hover align-middle">
                                        <thead class="table-dark text-center">
                                            <tr>
                                                <!-- <th width="5%">Profile</th> -->
                                                <th class="text-center">Users</th>
                                                <th class="text-center">User ID</th>
                                                <!-- <th width="40%">Email</th> -->
                                                <th class="text-center">Site</th>
                                                <th width class="text-center">Level</th>
                                                <!-- <th width="5%">Action</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($users as $user)
                                                <tr data-userid="{{ $user->userid }}" data-name="{{ $user->name }}"
                                                    data-email="{{ $user->email }}" data-site="{{ $user->rssite }}"
                                                    data-site_desc="{{ $user->rssite_desc }}" data-level="{{ $user->level }}"
                                                    data-gender="{{ $user->gender }}"
                                                    data-profile="{{ $user->profile_pic_url ? asset($user->profile_pic_url) : asset('uploads/user-profile/noprofile.png') }}"
                                                    data-create_date="{{ date('d F Y', strtotime($user->create_date)) }}">
                                                    <!-- <td class="text-center align-middle">
                                                            <img src="{{ $user->profile_pic_url ? asset($user->profile_pic_url) : asset('uploads/user-profile/noprofile.png') }}"
                                                                alt="profile" class="rounded-circle" width="50" height="50">
                                                        </td> -->
                                                    <td class="align-middle">
                                                        <img src="{{ $user->profile_pic_url ? asset($user->profile_pic_url) : asset('uploads/user-profile/noprofile.png') }}"
                                                            alt="profile" class="rounded-circle border border-3">
                                                        {{ $user->name }}
                                                    </td>
                                                    <td class="align-middle">{{ $user->userid }}</td>
                                                    <!-- <td class="align-middle">{{ $user->email }}</td> -->
                                                    <td class="align-middle text-center">
                                                        @if(!empty($user->logo_pic_url))
                                                            <img src="{{ asset($user->logo_pic_url) }}" alt="logo" class="me-1">
                                                        @endif
                                                        <!-- {{ $user->rssite_desc ?? $user->rssite }} -->
                                                    </td>
                                                    <td class="align-middle text-center">{{ $user->level }}</td>
                                                    <!-- <td class="align-middle text-center">
                                                            <button type="button" class="btn btn-sm btn-secondary btn-settings"
                                                                    data-userid="{{ $user->userid }}" title="Settings">
                                                                <i class="bi bi-gear-fill"></i>
                                                            </button>
                                                        </td> -->
                                                </tr>
                                            @empty
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

    <!-- Add User Modal (update the profile picture input section) -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <form action="{{ route('rsusers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h1 class="modal-title fs-5" id="addUserLabel">Add User</h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 col-md-4 text-center mb-3 mb-md-0 align-self-center">
                                <div class="mb-3 text-center" id="add-user-profile-preview-container">
                                    <img id="add-user-profile-preview" src="{{ asset('uploads/user-profile/noprofile.png') }}" alt="profile preview" class="rounded-circle mb-2 border" width="150" height="150">
                                </div>
                                <div class="mb-3 align-bottom">
                                    <label for="profile_pic_url" class="form-label">Profile Picture</label>
                                    <input type="file" name="profile_pic_url" id="profile_pic_url" class="form-control" accept="image/png, image/jpeg, image/jpg, image/webp">
                                    @error('profile_pic_url') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-8">
                                <div class="mb-3">
                                    <!-- <label for="rssite" class="form-label">Site</label> -->
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
                                    <!-- <label for="userid" class="form-label">User ID</label> -->
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                        <input type="text" class="form-control" id="userid" name="userid" value="{{ old('userid') }}" required maxlength="8" placeholder="User ID" autocomplete="off">
                                    </div>
                                @error('userid') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3">
                                    <!-- <label for="name" class="form-label">Name</label> -->
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" maxlength="255" placeholder="Name" autocomplete="off">
                                    </div>
                                    @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3">
                                    <!-- <label for="email" class="form-label">Email</label> -->
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" maxlength="255" required placeholder="Email" autocomplete="off">
                                    </div>
                                    @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3">
                                    <!-- <label for="gender" class="form-label">Gender</label> -->
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                                        <select name="gender" id="gender" class="form-select" placeholder="Gender">
                                            <option disabled selected>Select a gender</option>
                                            <option value="male" {{ old('gender')=='male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender')=='female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                    </div>
                                    @error('gender') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3">
                                    <!-- <label for="password" class="form-label">Password</label> -->
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input type="password" class="form-control" id="password" name="password" required maxlength="255" placeholder="Password" autocomplete="off">
                                    </div>
                                    @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                            </div>
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

    <!-- User View Modal -->
    <div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h1 class="modal-title fs-5" id="viewUserLabel"><span id="view-user-label-name">-</span></h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row align-items-center">
                        <div class="profile-col col-12 col-md-4 align-middle text-center border border-2 rounded py-5">
                            <img id="view-user-profile" src="{{ asset('uploads/user-profile/noprofile.png') }}"
                                alt="profile" class="profile-pic rounded-circle">
                        </div>
                        <div class="col-12 col-md-8">
                            <table class="table table-responsive mb-0 table-borderless">
                                <tbody>
                                    <tr>
                                        <th>Site:</th>
                                        <td id="view-user-site-detail">
                                            <span id="view-user-site_desc">-</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>User ID:</th>
                                        <td id="view-user-userid">
                                            <span id="view-user-id">-</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Name:</th>
                                        <td id="view-user-name-detail">
                                            <span id="view-user-name">-</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td id="view-user-email-detail">
                                            <span id="view-user-email">-</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Level:</th>
                                        <td id="view-user-level-detail">
                                            <span id="view-user-level">-</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Gender:</th>
                                        <td id="view-user-gender-detail">
                                            <span id="view-user-gender">-</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Date Created:</th>
                                        <td id="view-user-create-date-detail">
                                            <span id="view-user-create_date">-</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-danger" id="btnDeleteUser">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                    <button type="button" class="btn btn-warning" id="btnEditUser">
                        <i class="bi bi-pencil-square"></i> Edit
                    </button>
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                </div>
            </div>
        </div>
    </div>

    <!-- @if(isset($selectedUser))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('#view-user-profile').attr('src', '{{ asset($selectedUser->profile_pic_url) }}');
            $('#view-user-label-name').text('{{ $selectedUser->userid }}');
            $('#view-user-id').text('{{ $selectedUser->userid }}');
            $('#view-user-name').text('{{ $selectedUser->name }}');
            $('#view-user-email').text('{{ $selectedUser->email }}');
            $('#view-user-site_desc').text('{{ $selectedUser->rssite_desc }}');
            $('#view-user-level').text('{{ $selectedUser->level }}');
            $('#view-user-gender').text('{{ ucfirst($selectedUser->gender) }}');
            $('#view-user-create_date').text('{{ date('d F Y', strtotime($selectedUser->create_date)) }}');
            $('#viewUserModal').modal('show');
        });
    </script>
    @endif -->
@endsection
