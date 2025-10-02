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
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-pYdS1fsQ+dq3SqfqLDzPteHf+jaGiFjNPRMf5liROtfPH+9qlCw7HbJxZ5/2tZRk6cY0c7yqT+GLX2FmJ8eibw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
    @vite([
        'resources/css/app.css'
    ])
</head>

<body class="fixed-header fixed-footer bg-body-tertiary">
    <div id="app">
        {{-- Navbar --}}
        @include('partials.nav')

        {{-- Main Content --}}
        <main class="app-main">
            @yield('content')
        </main>

        {{-- Footer --}}
        @include('partials.footer')
    </div>
</body>

</html>
