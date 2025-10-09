@extends('irms/irms-partials.app')

@section('title', 'IRMS Profile')

@section('content')
<main class="app-main">
    <div class="app-content-wrapper">
        <div class="container-fluid py-4">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                        <!-- Top Profile Header -->
                        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between p-4"
                            style="background: linear-gradient(90deg, #e2e4e7ff 50%, #666666ff 100%);">
                            <div class="d-flex align-items-center w-100">
                                <!-- Clickable Profile Image -->
                                <a href="{{ $user->profile_pic_url }}" target="_blank" data-bs-toggle="modal" data-bs-target="#profilePicModal">
                                    <img src="{{ $user->profile_pic_url }}" alt="Profile Picture"
                                        class="rounded-circle border border-3 shadow me-4"
                                        style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;">
                                </a>
                                <div>
                                    <h4 class="mb-1">{{ $user->name }}</h4>
                                    <div class="text-muted small mb-1">{{ $user->email }}</div>
                                    <div class="fw-semibold">{{ $siteDesc ?? $user->rssite }}</div>
                                    <div class="text-muted small">Level: {{ $user->level }}</div>
                                </div>
                            </div>
                            <div class="mt-3 mt-md-0">
                                <!-- if(
                                    (Auth::check() && Auth::user()->userid === $user->userid)
                                    || Auth::user()->userid === 'sa'
                                ) -->
                                @if(auth()->user()->userid === 'sa')
                                    <a href=""
                                        class="btn btn-dark fw-bold px-4">Edit</a>
                                @endif
                            </div>
                        </div>
                        <!-- Profile Details -->
                        <div class="p-4">
                            <div class="row g-3">
                                @if(auth()->user()->userid === 'sa')
                                <div class="col-12 col-md-12">
                                    <label class="form-label fw-semibold">Site</label>
                                    <input type="text" class="form-control" value="{{ $siteDesc ?? $user->rssite }}"
                                        readonly>
                                </div>
                                @endif
                                <div class="col-12 col-md-12">
                                    <label class="form-label fw-semibold">Full Name</label>
                                    <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">User ID</label>
                                    <input type="text" class="form-control" value="{{ $user->userid }}" readonly>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Email</label>
                                    <input type="text" class="form-control" value="{{ $user->email }}" readonly>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Gender</label>
                                    <input type="text" class="form-control" value="{{ $user->gender }}" readonly>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Level</label>
                                    <input type="text" class="form-control" value="{{ $user->level }}" readonly>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-semibold">Department</label>
                                    <input type="text" class="form-control" value="{{ $user->department }}" readonly>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-semibold">Section</label>
                                    <input type="text" class="form-control" value="{{ $user->section }}" readonly>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-semibold">Position</label>
                                    <input type="text" class="form-control" value="{{ $user->position }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
</main>

<!-- Profile Picture Modal -->
<div class="modal fade" id="profilePicModal" tabindex="-1" aria-labelledby="profilePicModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-light border-0">
      <div class="modal-body text-center p-0">
        <img src="{{ $user->profile_pic_url }}" alt="Profile Picture Large" class="img-fluid rounded shadow">
      </div>
    </div>
  </div>
</div>
@endsection
