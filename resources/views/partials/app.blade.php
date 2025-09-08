<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'IRMS')</title>
    <!-- <link rel="stylesheet" href="../css/app.css"> -->
    <!-- <link rel="stylesheet" href="../bootstrap-5.0.2-dist/css/bootstrap.css"> -->
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @vite('resources/bootstrap-5.0.2-dist/css/bootstrap.css')
    @vite('resources/bootstrap-5.0.2-dist/js/bootstrap.js')
</head>
<body>
    <header>
        @include('partials.header')
        @yield('header')
    </header>

    <main>
        @yield('content')
    </main>
    
    <footer>
        @include('partials.footer')
    </footer>
</body>
</html>