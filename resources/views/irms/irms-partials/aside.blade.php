<!-- Sidebar -->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ url('/irms') }}" class="brand-link">
            <img src="{{ asset('uploads/img/irms.png') }}" alt="IRMS Logo" class="brand-image opacity-75 shadow rounded-circle" />
            <span class="brand-text fw-light">IRMS</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation" data-accordion="false" id="navigation">
                <li class="nav-item">
                    <a href="{{ url('/irms') }}" class="nav-link active">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-header">Manage Users</li>
                <li class="nav-item">
                    <a href="{{ url('/irms/manage-users') }}" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Users</p>
                    </a>
                </li>
                <li class="nav-header">Warehouse</li>
                <li class="nav-item">
                    <a href="{{ url('/irms/warehouse') }}" class="nav-link">
                        <i class="nav-icon fas fa-warehouse"></i>
                        <p>Warehouse</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-truck"></i>
                        <p>Warehouse Good Receiving</p>
                    </a>
                </li>
                <li class="nav-header">Location</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-map-marker-alt"></i>
                        <p>Bay Location</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/irms/rack-locations') }}" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>Rack Locations</p>
                    </a>
                </li>
                <li class="nav-item nav-logout">
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Log Out</p>
                    </a>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
    </div>

</aside>
