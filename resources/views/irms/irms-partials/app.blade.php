<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'IRMS')</title>
    <link rel="icon" type="image/png" href="{{ asset('uploads/img/irms.png') }}">

    @vite([
        'resources/css/app.css',
        'resources/css/irms.css',
    ])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
    <link href="https://cdn.datatables.net/columncontrol/1.1.0/css/columnControl.dataTables.min.css" rel="stylesheet">
</head>
<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <div class="app-wrapper">
        {{-- Navbar --}}
        @include('irms.irms-partials.nav')

        {{-- Sidebar --}}
        @include('irms.irms-partials.aside')

        <main class="app-main">
            {{-- Main Content --}}
            @yield('content')
        </main>

        {{-- Footer --}}
        @include('irms.irms-partials.footer')
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/columncontrol/1.1.0/js/dataTables.columnControl.min.js"></script>
    <script src="https://cdn.datatables.net/columncontrol/1.1.0/js/columnControl.dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/js/app.js'])
    <script>
        window.sessionCheckUrl = "{{ url('/irms/session') }}";
        window.loginUrl = "{{ route('login') }}";

        setInterval(function () {
            const currentPath = window.location.pathname;
            if (currentPath.indexOf("/irms") !== -1) {
                fetch(window.sessionCheckUrl)
                .then((response) => response.json())
                .then((data) => {
                    if (!data.valid) {
                        window.location.href = window.loginUrl;
                    }
                });
            }
        }, 5000);
    </script>
</body>
</html>
