<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto bg-opacity-50">

            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset(auth()->user()->profile_pic_url) }}"
                            class="user-image rounded-circle border border-2 border-opacity-25 me-2"
                            alt="{{ $user->userid ?? 'Guest User' }}-img">
                    <span class="d-none d-md-inline">{{ $user->name ?? 'Guest' }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end" aria-labelledby="userDropdown">
                    <li class="user-header text-center">
                        <img src="{{ asset(auth()->user()->profile_pic_url) }}"
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

<form id="logout-form" class="text-danger" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
