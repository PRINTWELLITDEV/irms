<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'IRMS')</title>
    <!-- <link rel="stylesheet" href="../css/app.css"> -->
    <!-- <link rel="stylesheet" href="../bootstrap-5.0.2-dist/css/bootstrap.css"> -->
    @vite([
        'resources/css/irms.css',
        'resources/bootstrap-5.0.2-dist/css/bootstrap.css',
        'resources/js/app.js',
        'resources/bootstrap-5.0.2-dist/js/bootstrap.js',
    ])

</head>
<body>
    <header>
        @include('irms/irms-partials.aside')
        @yield('aside')
    </header>

    <main>
        @yield('content')
    </main>
    
    <footer>
        
    </footer>
</body>
</html>