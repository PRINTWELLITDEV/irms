<form id="logout-form" method="POST" action="{{ route('logout') }}" class="d-none">
    @csrf
</form>
<!-- Sidebar -->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ url('/irms') }}" class="brand-link">
            <img src="{{ asset('uploads/img/irms.png') }}" alt="IRMS Logo" class="brand-image opacity-75 shadow rounded-circle" />
            <span class="brand-text">IRMS</span>
        </a>
    </div>
    <div class="sidebar-brand">
        @if(auth()->user()->userid === 'sa')
            <span class="brand-text fw-light">
                Super Admin
            </span>
        @else
            <span class="brand-text fw-light">
                {{ \App\Http\Controllers\Irms\IrmsController::getSiteDesc() }}
            </span>
        @endif
        
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation" data-accordion="false" id="navigation">
                <li class="nav-item">
                    <a href="{{ url('/irms') }}" class="nav-link{{ request()->is('irms') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @if(auth()->user()->userid === 'sa')
                <li class="nav-header">Manage Users</li>
                <li class="nav-item">
                    <a href="{{ url('/irms/manage-users') }}" class="nav-link{{ request()->is('irms/manage-users') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Users</p>
                    </a>
                </li>
                @endif
                <li class="nav-header">Warehouse and Locations</li>
                <li class="nav-item">
                    <a href="{{ url('/irms/warehouse') }}" class="nav-link{{ request()->is('irms/warehouse') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-warehouse"></i>
                        <p>Warehouse</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/irms/bay-locations') }}" class="nav-link{{ request()->is('irms/bay-locations') ? ' active' : '' }}">
                        <i class="nav-icon bi bi-box-seam"></i>
                        <p>Bay Locations</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/irms/rack-locations') }}" class="nav-link{{ request()->is('irms/rack-locations') ? ' active' : '' }}">
                        <i class="nav-icon bi bi-grid-3x3"></i>
                        <p>Rack Locations</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/irms/item-locations') }}" class="nav-link{{ request()->is('irms/item-locations') ? ' active' : '' }}">
                        <i class="nav-icon bi bi-list"></i>
                        <p>Item Locations</p>
                    </a>
                </li>
                <li class="nav-header">Receiving and Dispatching</li>
                <li class="nav-item">
                    <a href="{{ url('/irms/receiving') }}" class="nav-link{{ request()->is('irms/receiving') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-truck"></i>
                        <p>Goods Receiving</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/irms/dispatching') }}" class="nav-link{{ request()->is('irms/dispatching') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-truck"></i>
                        <p>Goods Dispatching</p>
                    </a>
                </li>
                <li class="nav-header">Transactions</li>
                <li class="nav-item">
                    <a href="{{ url('/irms/transactions') }}" class="nav-link{{ request()->is('irms/transactions') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-exchange-alt"></i>
                        <p>Transactions</p>
                    </a>
                </li>
                <!-- <li class="nav-header"></li>
                <li class="nav-item nav-logout">
                    @auth
                    <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Log Out</p>
                    </a>
                    @endauth
                </li> -->
            </ul> 
        </nav>
    </div>

</aside>
