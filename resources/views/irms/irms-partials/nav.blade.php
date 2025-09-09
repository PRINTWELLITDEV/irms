<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <!-- <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ url('/irms') }}" class="nav-link">Home</a>
        </li>
    </ul> -->

    <!-- Right navbar links -->
    <ul class="navbar-nav ms-auto">
        <!-- Notifications Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="far fa-bell"></i>
                <span class="badge bg-warning rounded-pill">3</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
                <li><h6 class="dropdown-header">3 Notifications</h6></li>
                <li><hr class="dropdown-divider"></li>
                <li><a href="#" class="dropdown-item">
                    <i class="fas fa-envelope me-2"></i> 1 new message
                    <span class="float-end text-muted text-sm">3 mins</span>
                </a></li>
                <li><a href="#" class="dropdown-item">
                    <i class="fas fa-users me-2"></i> 2 friend requests
                    <span class="float-end text-muted text-sm">12 hours</span>
                </a></li>
                <li><a href="#" class="dropdown-item">
                    <i class="fas fa-file me-2"></i> 1 new report
                    <span class="float-end text-muted text-sm">2 days</span>
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a href="#" class="dropdown-item text-center">See All Notifications</a></li>
            </ul>
        </li>

        <!-- User Dropdown -->
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ session('user.profile_pic_url') 
                                ? asset(session('user.profile_pic_url')) 
                                : asset('uploads/user-profile/guest.png') }}" 
                    class="user-image rounded-circle" 
                    alt="{{ session('user.userid') ?? 'Guest User' }}-img"
                    width="30" height="30">

                <span class="d-none d-md-inline">{{ session('user.name') ?? 'Guest' }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <!-- User image -->
                <li class="dropdown-header text-center">
                    <img src="{{ session('user.profile_pic_url') 
                                ? asset(session('user.profile_pic_url')) 
                                : asset('uploads/user-profile/guest.png') }}" 
                         class="user-image rounded-circle" 
                         alt="{{ session('user.userid') ?? 'Guest User' }}-img"
                         width="100" height="100">
                    <p class="mb-0">
                        <small>User ID: {{ session('user.userid') ?? 'Guest ID' }} </small><br>
                        {{ session('user.name') ?? 'Guest User' }} <br>
                        <small>Site: {{ session('user.rssite') ?? 'N/A' }}</small>
                    </p>
                </li>
                <li><hr class="dropdown-divider"></li>
                <!-- Menu Footer-->
                <li class="d-flex justify-content-between px-3">
                    <a href="#" class="btn btn-outline-primary btn-sm">Profile</a>
                    <a href="{{ route('logout') }}" class="btn btn-outline-danger btn-sm"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Log Out
                    </a>
                </li>
            </ul>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</nav>
