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
        'resources/js/app.js',
    ])

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- DataTables CDN -->
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css"> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">

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

    <script>
        window.sessionCheckUrl =  "{{ url('/irms/session') }}";
        window.loginUrl = "{{ route('login') }}";
    </script>

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- DataTables CDN -->
    <!-- <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script> -->
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

    <!-- Chart.js CDN -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
</body>
</html>
