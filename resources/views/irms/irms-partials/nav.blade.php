@php
    use Illuminate\Support\Facades\Storage;
    $user = $user ?? auth()->user();
    $siteDesc = $siteDesc ?? null;
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
            <!-- <li class="nav-item dropdown">
                <a class="nav-link" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
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
            </li> -->
            <!-- <li class="nav-item">
                <a class="nav-link" id="fullscreenToggle" href="#" data-lte-toggle="fullscreen">
                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                </a>
            </li> -->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ $user->profile_pic_url }}"
                            class="user-image rounded-circle border border-2 border-opacity-25 me-2"
                            alt="{{ $user->userid ?? 'Guest User' }}-img">
                    <span class="d-none d-md-inline">{{ $user->name ?? 'Guest' }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end" aria-labelledby="userDropdown">
                    <li class="user-header text-center">
                        <img src="{{ $user->profile_pic_url }}"
                                class="user-image rounded-circle shadow"
                                alt="{{ $user->userid ?? 'Guest User' }}-img" />
                        <div class="fw-bold">{{ $user->name ?? 'Guest User' }}</div>
                        <div class="text-muted small">User ID: {{ $user->userid ?? 'Guest ID' }}</div>
                        <small>{{ $siteDesc ?? 'N/A' }}</small>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li class="d-flex justify-content-center gap-5 p-2">
                        @auth
                            <a href="{{ route('irms.userprofile', ['userid' => $user->userid]) }}" class="btn btn-outline-primary btn-sm">Profile</a>
                            <a href="#" class="btn btn-outline-danger btn-sm"
                                onclick="event.preventDefault(); document.getElementById('logout-form-top').submit();">
                                Log Out
                            </a>
                        @endauth
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login</a>
                        @endguest
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<form id="logout-form-top" class="text-danger" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
