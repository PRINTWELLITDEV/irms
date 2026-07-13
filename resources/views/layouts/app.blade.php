<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'IRMS') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('uploads/img/irms.png') }}">
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="aos-master/dist/aos.css" rel="stylesheet"> @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="@yield('body-class', 'fixed-header fixed-footer')">

    <div id="app-wrapper">
        {{-- Navbar --}}
        @include('partials.nav')

        {{-- Main Content --}}
        <main class="app-main">
            @yield('content')
        </main>

        {{-- Footer --}}
        @include('partials.footer')
    </div>

    <script src="aos-master/dist/aos.js"></script>
    <script>
        window.appUrl = "{{ url('') }}";
        AOS.init({
            duration: 1200, // Duration of the animation
            once: true,     // Whether animation should happen only once - true is usually better for landing pages
        });
    </script>
</body>

</html>
