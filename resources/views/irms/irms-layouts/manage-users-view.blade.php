@extends('irms.irms-partials.app')
@section('title', 'IRMS')
@section('content')
<div class="wrapper">
    <div class="content-wrapper visible">
        <div class="content-header">
            <div class="container-fluid">
                <h1>View User</h1>
            </div>
        </div>
        <div class="content-body">
            <!-- Modal structure, but always visible -->
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h1 class="modal-title fs-5">User: {{ $user->name }}</h1>
                    </div>
                    <div class="modal-body">
                        <div class="row align-items-center">
                            <div class="col-12 col-md-4 text-center mb-3 mb-md-0">
                                <img src="{{ asset($user->profile_pic_url) }}" alt="profile" class="profile-pic rounded-circle mb-2">
                            </div>
                            <div class="col-12 col-md-8">
                                <table class="table table-responsive mb-0 table-borderless">
                                    <tbody>
                                        <tr>
                                            <th>Site:</th>
                                            <td>{{ $user->rssite_desc }}</td>
                                        </tr>
                                        <tr>
                                            <th>User ID:</th>
                                            <td>{{ $user->userid }}</td>
                                        </tr>
                                        <tr>
                                            <th>Name:</th>
                                            <td>{{ $user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>{{ $user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Level:</th>
                                            <td>{{ $user->level }}</td>
                                        </tr>
                                        <tr>
                                            <th>Gender:</th>
                                            <td>{{ ucfirst($user->gender) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Date Created:</th>
                                            <td>{{ date('d F Y', strtotime($user->create_date)) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                        <button type="button" class="btn btn-warning">
                            <i class="bi bi-pencil-square"></i> Edit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection