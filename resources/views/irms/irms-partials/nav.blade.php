@php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::user();
@endphp
<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto">
            <!-- Notifications Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="far fa-bell"></i>
                    <span class="badge bg-warning rounded-pill">3</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <span class="dropdown-item dropdown-header">15 Notifications</span>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                    <i class="bi bi-envelope me-2"></i> 4 new messages
                    <span class="float-end text-secondary fs-7">3 mins</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                    <i class="bi bi-people-fill me-2"></i> 8 friend requests
                    <span class="float-end text-secondary fs-7">12 hours</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                    <i class="bi bi-file-earmark-fill me-2"></i> 3 new reports
                    <span class="float-end text-secondary fs-7">2 days</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer"> See All Notifications </a>
                </div>
            </li>
            <!--begin::Fullscreen Toggle-->
            <li class="nav-item">
              <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
              </a>
            </li>
            <!--end::Fullscreen Toggle-->
            <!-- User Dropdown -->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ $user && $user->profile_pic_url 
                                    ? asset($user->profile_pic_url) 
                                    : asset('uploads/user-profile/guest.png') }}" 
                        class="user-image rounded-circle" 
                        alt="{{ $user->userid ?? 'Guest User' }}-img"
                        width="30" height="30">
                    <span class="d-none d-md-inline">{{ $user->name ?? 'Guest' }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li class="dropdown-header text-center">
                        <img src="{{ $user && $user->profile_pic_url 
                                        ? asset($user->profile_pic_url) 
                                        : asset('uploads/user-profile/guest.png') }}" 
                             class="user-image rounded-circle" 
                             alt="{{ $user->userid ?? 'Guest User' }}-img"
                             width="100" height="100">
                        <p class="mb-0">
                            <small>User ID: {{ $user->userid ?? 'Guest ID' }} </small><br>
                            {{ $user->name ?? 'Guest User' }} <br>
                            <small>Site: {{ $user->rssite ?? 'N/A' }}</small>
                        </p>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li class="d-flex justify-content-between px-3">
                        <a href="#" class="btn btn-outline-primary btn-sm">Profile</a>
                        @auth
                            <a href="{{ route('logout') }}" class="btn btn-outline-danger btn-sm"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Log Out
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        @endauth
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
