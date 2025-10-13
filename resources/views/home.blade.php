@extends('layouts.app')
@section('title', 'IRMS')
@section('content')

    {{-- Fullscreen background carousel --}}
    <div id="backgroundCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3000">
        <div class="carousel-inner">

            <div class="carousel-item active">
                <img src="{{ asset('uploads/img/rack_wallpaper_4.jpg') }}" class="d-block w-100 vh-100 object-fit-cover" alt="Warehouse 1">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('uploads/img/rack_wallpaper_5.jpg') }}" class="d-block w-100 vh-100 object-fit-cover" alt="Warehouse 2">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('uploads/img/rack_wallpaper_6.jpg') }}" class="d-block w-100 vh-100 object-fit-cover" alt="Warehouse 3">
            </div>

        </div>
    </div>

    {{-- Overlay Content on top of carousel --}}
    <div class="glass-bg-overlay d-flex align-items-center justify-content-center text-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-8 text-center glass-panel p-5 rounded-4 shadow-lg">

                    <h1 class="display-3 fw-bold mb-3 text-shadow">IRMS</h1>
                    <p class="lead mb-5 pb-3 text-shadow">
                        Your Integrated Inventory and Racking Management System.
                    </p>

                    @guest
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5 glass-button">Login</a>
                    @else
                        <div class="row g-4 justify-content-center mt-5 pt-3">
                            <div class="col-sm-6 col-md-5">
                                <a href="{{ route('inventory.index') }}" class="card text-decoration-none glass-card rounded-4">
                                    <div class="card-body p-4 text-start position-relative">
                                        <h4 class="card-title mb-1 text-shadow">Inventory</h4>
                                        <p class="text-white-75 small">Access and manage all stock items and movements.</p>
                                        <i class="fas fa-cubes fa-2x text-white position-absolute end-0 bottom-0 m-3 opacity-25" aria-hidden="true"></i>
                                    </div>
                                </a>
                            </div>

                            <div class="col-sm-6 col-md-5">
                                <a href="{{ route('dashboard') }}" class="card text-decoration-none glass-card rounded-4">
                                    <div class="card-body p-4 text-start position-relative">
                                        <h4 class="card-title mb-1 text-shadow">Dashboard</h4>
                                        <p class="text-white-75 small">Visualize key metrics and performance data.</p>
                                        <i class="fas fa-chart-bar fa-2x text-white position-absolute end-0 bottom-0 m-3 opacity-25" aria-hidden="true"></i>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endguest

                </div>
            </div>
        </div>
    </div>
@endsection
