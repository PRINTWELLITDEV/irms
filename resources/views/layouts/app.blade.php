<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'IRMS') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('uploads/img/irms.png') }}">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-pYdS1fsQ+dq3SqfqLDzPteHf+jaGiFjNPRMf5liROtfPH+9qlCw7HbJxZ5/2tZRk6cY0c7yqT+GLX2FmJ8eibw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    @vite([
        'resources/css/app.css',
        'resources/css/irms.css',
        'resources/js/app.js',
    ])

    <style>
        /* smoother content reveal */
        .content-wrapper {
            opacity: 0;
            transform: translateY(8px);
            transition: opacity 450ms cubic-bezier(.22, 1, .36, 1), transform 450ms cubic-bezier(.22, 1, .36, 1);
            will-change: opacity, transform;
        }

        .content-wrapper.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* smoother card hover */
        .card {
            transition: transform 350ms cubic-bezier(.22, 1, .36, 1), box-shadow 350ms cubic-bezier(.22, 1, .36, 1);
            will-change: transform, box-shadow;
        }

        .card:hover {
            transform: translateY(-6px) translateZ(0);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
        }

        /* small improvement for modals / dropdowns */
        .dropdown-menu,
        .modal-content {
            transition: transform 260ms cubic-bezier(.22, 1, .36, 1), opacity 260ms ease;
            will-change: transform, opacity;
        }

        /* Sidebar width + smooth collapse */
        .app-sidebar {
            width: 230px;
            transition: width 300ms cubic-bezier(.22, 1, .36, 1), opacity 250ms ease;
            will-change: width, opacity;
            overflow: hidden;
        }

        /* collapsed (icon-only) width */
        .app-sidebar.sidebar-mini {
            width: 64px;
        }

        /* temporary class during transition (optional) */
        .app-sidebar.sidebar-collapsing {
            transition-duration: 320ms;
        }

        /* hide text labels when collapsed */
        .app-sidebar.sidebar-mini .brand-text,
        .app-sidebar.sidebar-mini .sidebar-wrapper .nav-link p,
        .app-sidebar.sidebar-mini .sidebar-wrapper .nav-header {
            opacity: 0;
            transition: opacity 180ms ease;
            pointer-events: none;
        }

        /* keep icons visible and centered */
        .app-sidebar .nav-icon,
        .app-sidebar .brand-image {
            transition: transform 300ms ease;
        }

        /* adjust content area if you use left margin */
        .content-wrapper {
            transition: margin-left 300ms cubic-bezier(.22, 1, .36, 1);
        }

        /* when collapsed reduce left offset (adjust selector according your layout) */
        body.sidebar-mini .content-wrapper {
            margin-left: 64px;
            /* match collapsed aside width */
        }

        /* when expanded ensure original offset */
        body:not(.sidebar-mini) .content-wrapper {
            margin-left: 230px;
            /* match full aside width */
        }
    </style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'IRMS') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
        @include('partials.footer')

    </div>
</body>

</html>
