<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'IRMS')</title>
    <link rel="icon" type="image/png" href="{{ asset('uploads/img/irms.png') }}">

    {{-- 1. Vite assets for local/development files (Keep this) --}}
    @vite([
        'resources/css/irms.css',
        'resources/js/irms/irms.js'
    ])

    {{-- 2. Performance: Preload/Preconnect for critical CDNs --}}
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://cdn.datatables.net">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">

    {{-- 3. Load critical CSS libraries using CDNs (Use integrity hashes) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" {{-- OLD
        HASH: integrity="..." --}} {{-- NEW CORRECT HASH: --}}
        integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
    <link href="https://cdn.datatables.net/columncontrol/1.1.0/css/columnControl.dataTables.min.css" rel="stylesheet">

    {{-- 4. Yield CSS for page-specific styles --}}
    @stack('styles')
</head>

<body class="layout-fixed fixed-header fixed-footer sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        {{-- Navbar --}}
        @include('irms.irms-partials.nav')

        {{-- Sidebar --}}
        @include('irms.irms-partials.aside')

        {{-- Main Content --}}
        @yield('content')

        {{-- Footer --}}
        @include('irms.irms-partials.footer')
    </div>

    {{-- 5. Load jQuery FIRST (Many plugins depend on it) --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    {{-- 6. Load Core Libraries (Place Chart.js before DataTables as it's a plotting tool) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- DataTables JS (Must come after jQuery) --}}
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/columncontrol/1.1.0/js/dataTables.columnControl.min.js"></script>
    <script src="https://cdn.datatables.net/columncontrol/1.1.0/js/columnControl.dataTables.js"></script>

    {{-- 7. Global JavaScript Definitions --}}
    <script>
        function createChart(canvasId, configFn) {
            const ctx = document.getElementById(canvasId);
            if (!ctx) return;
            new Chart(ctx, configFn());
        }

        // Global variables/configs
        window.appUrl = "{{ url('') }}";
        window.sessionCheckUrl = "{{ url('/irms/session') }}";
        window.loginUrl = "{{ route('login') }}";
    </script>

    {{-- 8. Yield Scripts (This is where your dashboard and page-specific JS run) --}}
    @stack('scripts')
</body>

</html>
