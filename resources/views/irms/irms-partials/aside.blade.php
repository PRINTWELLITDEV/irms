<!-- Sidebar -->
<aside class="main-sidebar">
    <div class="sidebar">
        <div class="sidebar-header">
            <h3>IRMS</h3>
        </div>
        <nav>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ url('/irms') }}" class="nav-link">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/irms/warehouse') }}" class="nav-link">
                        <i class="fas fa-warehouse"></i> Warehouse
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/irms/rack-locations') }}" class="nav-link">
                        <i class="fas fa-boxes"></i> Rack Locations
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/irms/manage-users') }}" class="nav-link">
                        <i class="fas fa-boxes"></i> Users
                    </a>
                </li>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link text-start">
                        <i class="fas fa-boxes"></i> Log Out
                    </button>
                </form>
            </ul>
        </nav>
    </div>
</aside>