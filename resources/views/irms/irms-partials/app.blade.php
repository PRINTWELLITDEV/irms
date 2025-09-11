<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'IRMS')</title>
    @vite([
        'resources/sass/app.scss',
        'resources/css/irms.css',
        'resources/js/app.js',
    ])

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <div class="app-wrapper">
        {{-- Navbar --}}
        @include('irms.irms-partials.nav')

        {{-- Sidebar --}}
        @include('irms.irms-partials.aside')

        {{-- Main Content --}}
        <main class="app-main">
            @yield('content')
        </main>

        {{-- Footer --}}
        @include('irms.irms-partials.footer')
    </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const wrapper = document.querySelector('.content-wrapper');
        if(wrapper) {
            wrapper.style.opacity = 0;
            setTimeout(() => {
                wrapper.style.opacity = 1;
            }, 50);
        }
    });
</script>
</html>