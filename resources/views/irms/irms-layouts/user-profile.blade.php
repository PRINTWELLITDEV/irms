@extends('irms/irms-partials.app')

@section('title', 'IRMS Profile')

@section('content')
<main class="app-main">
    <div class="app-content-wrapper">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col mb-3 d-flex align-items-center">
                        <h1 class="d-inline-block mb-0 me-3">Profile</h1>
                        @if(session('success'))
                            <div id="success-alert" class="alert alert-success py-1 px-3 mb-0" style="transition: opacity 0.7s;">
                                {{ session('success') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="app-content-body">
            <div class="container-fluid">
                <div class="row g-3">
                    <div class="col-12 col-md-4 d-flex flex-column">
                        <div class="flex-grow-1 mb-3 p-0">
                            <div class="p-4 h-100 d-flex flex-column profile-menu">
                                <div class="d-flex align-items-center mb-3">
                                    <a href="{{ $user->profile_pic_url }}" target="_blank" data-bs-toggle="modal" data-bs-target="#profilePicModal">
                                        <img src="{{ $user->profile_pic_url }}" alt="Profile" class="rounded-circle" style="width:70px;height:70px;object-fit:cover;">
                                    </a>
                                    <div class="ms-3">
                                        <div class="fw-bold fs-5">{{ $user->name }}</div>
                                        <div class="text-muted">{{ $user->position }}</div>
                                    </div>
                                </div>
                                <div class="row text-muted mb-3">
                                    <div class="col-1 text-center"><i class="bi bi-person"></i></div>
                                    <div class="col-2">User</div>
                                    <div class="col-9 text-end text-dark fw-semibold">{{ $user->userid }}</div>
                                </div>
                                <div class="row text-muted mb-3">
                                    <div class="col-1 text-center"><i class="bi bi-envelope"></i></div>
                                    <div class="col-2">Email</div>
                                    <div class="col-9 text-end text-dark fw-semibold">{{ $user->email }}</div>
                                </div>
                                <div class="row text-muted mb-4">
                                    <div class="col-1 text-center"><i class="bi bi-geo-alt"></i></div>
                                    <div class="col-2">Site</div>
                                    <div class="col-9 text-end text-dark fw-semibold">{{ $siteDesc ?? $user->rssite }}</div>
                                </div>
                                <div class="row text-center border-top border-bottom">
                                    <div class="list-group list-group-flush" id="profileTab" role="tablist">
                                        <a href="#overview" class="list-group-item list-group-item-action d-flex align-items-center active"
                                           data-bs-toggle="tab" role="tab">
                                            <i class="bi bi-person-lines-fill me-2"></i> Profile Overview
                                            <span class="ms-auto"><i class="bi bi-chevron-right"></i></span>
                                        </a>
                                        @if(Auth::user() == $user || Auth::user()->userid == 'sa')
                                        <a href="#personal" class="list-group-item list-group-item-action d-flex align-items-center"
                                           data-bs-toggle="tab" role="tab">
                                            <i class="bi bi-file-earmark-person me-2"></i> Personal Information
                                            <span class="ms-auto"><i class="bi bi-chevron-right"></i></span>
                                        </a>
                                        <a href="#account" class="list-group-item list-group-item-action d-flex align-items-center"
                                           data-bs-toggle="tab" role="tab">
                                            <i class="bi bi-person-badge me-2"></i> Account Information
                                            <span class="ms-auto"><i class="bi bi-chevron-right"></i></span>
                                        </a>
                                        <a href="#password" class="list-group-item list-group-item-action d-flex align-items-center"
                                           data-bs-toggle="tab" role="tab">
                                            <i class="bi bi-shield-lock me-2"></i> Change Password
                                            <span class="ms-auto"><i class="bi bi-chevron-right"></i></span>
                                        </a>
                                        <a href="#email" class="list-group-item list-group-item-action d-flex align-items-center"
                                           data-bs-toggle="tab" role="tab">
                                            <i class="bi bi-envelope me-2"></i> Email settings
                                            <span class="ms-auto"><i class="bi bi-chevron-right"></i></span>
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="h-100 profile-tab-content">
                            <div class="tab-content h-100" id="profileTabContent">
                                <div class="tab-pane fade show active" id="overview" role="tabpanel">
                                    <div class="card-header border-bottom-1 fw-semibold">
                                        <h5 class="mb-1 fw-semibold">
                                            <i class="bi bi-person-lines-fill me-2 text-success"></i> About Me
                                        </h5>
                                    </div>
                                    <div class="card-body p-4 text-secondary">
                                        <h5 class="fw-semibold mb-3">Personal Details</h5>
                                        <div class="row">
                                            <div class="col-12 col-md-10">
                                                <div class="row mb-2">
                                                    <div class="col-4 col-sm-4 fw-semibold">Full Name</div>
                                                    <div class="col-1 text-center">:</div>
                                                    <div class="col-7 col-sm-7 text-dark">{{ $user->name ?? '-' }}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-4 col-sm-4 fw-semibold">Company</div>
                                                    <div class="col-1 text-center">:</div>
                                                    <div class="col-7 col-sm-7 text-dark">{{ $siteDesc ?? $user->rssite ?? '-' }}</div>
                                                </div>

                                                <div class="row mb-2">
                                                    <div class="col-4 col-sm-4 fw-semibold">Company Address</div>
                                                    <div class="col-1 text-center">:</div>
                                                    <div class="col-7 col-sm-7 text-dark">{{ $siteAddress ?? '-' }}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-4 col-sm-4 fw-semibold">Email</div>
                                                    <div class="col-1 text-center">:</div>
                                                    <div class="col-7 col-sm-7 text-dark">{{ $user->email ?? 'support@example.com' }}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-4 col-sm-4 fw-semibold">Department</div>
                                                    <div class="col-1 text-center">:</div>
                                                    <div class="col-7 col-sm-7 text-dark">{{ $user->department ?? '-' }}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-4 col-sm-4 fw-semibold">Section</div>
                                                    <div class="col-1 text-center">:</div>
                                                    <div class="col-7 col-sm-7 text-dark">{{ $user->section ?? '-' }}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-4 col-sm-4 fw-semibold">Position</div>
                                                    <div class="col-1 text-center">:</div>
                                                    <div class="col-7 col-sm-7 text-dark">{{ $user->position ?? '-' }}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-4 col-sm-4 fw-semibold">Joined IRMS</div>
                                                    <div class="col-1 text-center">:</div>
                                                    <div class="col-7 col-sm-7 text-dark">{{ \Carbon\Carbon::parse($user->created_at)->format('d F Y') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="personal" role="tabpanel">
                                    <div class="card-header fw-semibold">
                                        <h5>
                                            <i class="bi bi-file-earmark-person me-2 text-success"></i>
                                            Personal Information
                                        </h5>
                                    </div>
                                    <div class="card-body p-4 text-secondary">
                                        <form method="POST" action="{{ route('user-profile.update', $user->userid) }}">
                                            @csrf
                                            <div class="row">
                                                <div class="col-12 col-md-12 mb-2">
                                                    <label class="form-label fw-semibold">Full Name</label>
                                                    <input type="text" class="form-control" name="name" value="" placeholder="{{ $user->name }}">
                                                </div>
                                                <div class="col-12 col-md-12 mb-2">
                                                    <label class="form-label fw-semibold">Gender</label>
                                                    <select name="gender" class="form-control">
                                                        <option value="{{ $user->gender ?? '' }}">{{ $user->gender ?? '- Select Gender -'}}</option>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                    </select>
                                                </div>
                                                <div class="col-12 col-md-12 mb-2">
                                                    <label class="form-label fw-semibold">Department</label>
                                                    <input type="text" class="form-control" name="department" value="" placeholder="{{ $user->department }}">
                                                </div>
                                                <div class="col-12 col-md-12 mb-2">
                                                    <label class="form-label fw-semibold">Section</label>
                                                    <input type="text" class="form-control" name="section" value="" placeholder="{{ $user->section }}">
                                                </div>
                                                <div class="col-12 col-md-12 mb-2">
                                                    <label class="form-label fw-semibold">Position</label>
                                                    <input type="text" class="form-control" name="position" value="" placeholder="{{ $user->position }}">
                                                </div>
                                                <div class="col-12 text-end">
                                                    <button class="btn btn-success">Update Profile</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="account" role="tabpanel">
                                    <div class="card-header fw-semibold">
                                        <h5> <i class="bi bi-person-badge me-2 text-success"></i>
                                            Account Information
                                        </h5>
                                    </div>
                                    <div class="card-body p-4 text-secondary">
                                        <form method="POST" action="">
                                            @csrf
                                            <div class="row">
                                                <div class="col-12 col-md-12 mb-3">
                                                    <label class="form-label fw-semibold">User ID</label>
                                                    <input type="text" class="form-control" name="userid" value="" placeholder="{{ $user->userid }}">
                                                </div>
                                                <div class="col-12 col-md-12 mb-3">
                                                    <label class="form-label fw-semibold">Account Email</label>
                                                    <input type="email" class="form-control" name="email" value="" placeholder="{{ $user->email }}">
                                                </div>
                                                @if(Route::has('update.accountinfo'))
                                                <div class="col-12 mt-3 text-end">
                                                    <button class="btn btn-success">Update Profile</button>
                                                </div>
                                                @endif
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="password" role="tabpanel">
                                    <div class="card-header fw-semibold">
                                        <h5>
                                            <i class="bi bi-lock-fill me-2 text-success"></i>
                                            Change Password
                                        </h5>
                                    </div>
                                    <div class="card-body p-4 text-secondary">
                                        <form method="POST" action="">
                                            @csrf
                                            <div class="row">
                                                <div class="col-12 col-md-12 mb-3">
                                                    <label class="form-label fw-semibold">Current Password</label>
                                                    <input type="password" class="form-control" name="current_password" placeholder="Enter current password">
                                                </div>
                                                <div class="col-12 col-md-12 mb-3">
                                                    <label class="form-label fw-semibold">New Password</label>
                                                    <input type="password" class="form-control" name="new_password" placeholder="Enter new password">
                                                </div>
                                                <div class="col-12 col-md-12 mb-3">
                                                    <label class="form-label fw-semibold">Confirm New Password</label>
                                                    <input type="password" class="form-control" name="confirm_new_password" placeholder="Confirm new password">
                                                </div>
                                                <div class="col-12 mt-3 text-end">
                                                    <button class="btn btn-success">Change Password</button>
                                                    <button class="btn btn-secondary">Reset</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="email" role="tabpanel">
                                    <div class="card-header fw-semibold">
                                        <h5>
                                            <i class="bi bi-envelope-fill me-2 text-success"></i>
                                            Email Settings
                                        </h5>
                                    </div>
                                    <div class="card-body text-secondary">
                                        <p>This is the email settings content.</p>
                                    </div>
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
