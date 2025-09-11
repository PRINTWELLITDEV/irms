@extends('irms.irms-partials.app')
@section('title', 'IRMS Dashboard')

@section('content')
    <div class="wrapper">
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="content-header">
                <h1>Manage Users</h1>
            </div>
            <div class="content-body">
                <div class="container-fluid">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-12 text-center mt-2 mb-5">
                            <!-- Header Row (3 columns) -->
                            <div class="row text-start d-flex justify-center align-items-center flex-wrap py-3">
                                <div class="col">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-secondary dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-funnel-fill"></i>
                                            Order By
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="#" class="dropdown-item">Name</a></li>
                                            <li><a href="#" class="dropdown-item">User ID</a></li>
                                            <li><a href="#" class="dropdown-item">Recently Added</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col">
                                    <form id="searchForm" method="GET" action="{{ route('rsusers.index') }}"
                                        class="input-group d-flex justify-content-center align-items-center gap-0">
                                        <div class="w-50">
                                            <input id="search" name="search" type="text" class="form-control"
                                                value="{{ request('search') }}" autocomplete="off" />
                                        </div>
                                        <button type="submit" class="btn btn-warning form-label mt-2">
                                            <i class="bi bi-search"></i>
                                            Search
                                        </button>
                                    </form>
                                </div>
                                <div class="col text-end">
                                    <button type="button" id="btnAddUsername" class="btn btn-success" data-bs-toggle="modal"
                                        data-bs-target="#btnAddUser">
                                        <i class="bi bi-person-plus-fill"></i>
                                        Add User
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th scope="col">Site</th>
                                            <th scope="col">User ID</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">User Type</th>
                                            <th scope="col">Level</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="users-table-body">
                                        @forelse($users as $user)
                                            <tr>
                                                <td>{{ $user->rssite }}</td>
                                                <td>{{ $user->userid }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->user_type }}</td>
                                                <td>{{ $user->level }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary">Edit</button>
                                                    <button class="btn btn-sm btn-danger">Delete</button>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->

    <div class="modal fade" id="btnAddUser" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('RsUserController.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Modal Header -->
                    <div class="modal-header bg-success text-white">
                        <h1 class="modal-title fs-5" id="addUserLabel">Add User</h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>

<<<<<<< HEAD
                <!-- Modal Header -->
                <div class="modal-header bg-success text-white">
                    <h1 class="modal-title fs-5" id="addUserLabel">Add User</h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form action="" method="POST">
                        @csrf
=======
                    <!-- Modal Body -->
                    <div class="modal-body">
>>>>>>> dev/manage-users
                        <div class="mb-3">
                            <label for="prof_pic_url" class="form-label">Profile Picture</label>
                            <input type="file" name="prof_pic_url" id="prof_pic_url" class="form-control">
                        </div>
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
                            <label for="userid" class="form-label">User ID</label>
                            <input type="text" class="form-control" id="userid" name="userid" required>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label">Email</label>
                            <select name="gender" id="gender" class="form-select">
                                <option disabled selected>Select a gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="user_type" class="form-label">User Type</label>
                            <select name="user_type" id="user_type" required class="form-select">
                                <option disabled selected>Select User Type</option>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                                <option value="superadmin">Super Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="level" class="form-label">Level</label>
                            <input type="text" class="form-control" id="level" name="level">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
