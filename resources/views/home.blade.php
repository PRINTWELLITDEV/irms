
@extends('layouts.app')
@section('title', 'IRMS')
@section('content')
    {{-- Main wrapper with the background image/gradient for the glass effect --}}
    <div class="glass-bg-container d-flex align-items-center" id="home-background">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-8 text-center glass-panel p-5 rounded-4 shadow-lg">

                    {{-- Title/Tagline with strong contrast for readability --}}
                    <h1 class="display-3 fw-light mb-3 text-shadow">
                        IRMS
                    </h1>
                    <p class="lead mb-5 pb-3 text-shadow">
                        Your Integrated Inventory and Racking Management System.
                    </p>

                    @guest
                        {{-- Login button with an accent color and glass styling --}}
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5 glass-button">
                            Login
                        </a>
                    @else
                        {{-- Logged-in Dashboard Links (Glass Cards) --}}
                        <div class="row g-4 justify-content-center mt-5 pt-3">

                            {{-- Inventory Card --}}
                            <div class="col-sm-6 col-md-5">
                                <a href="{{ route('inventory.index') }}" class="card text-decoration-none glass-card rounded-4">
                                    <div class="card-body p-4 text-start position-relative">
                                        <h4 class="card-title mb-1 text-shadow">Inventory</h4>
                                        <p class="text-white-75 small">Access and manage all stock items and movements.</p>
                                        <i class="fas fa-cubes fa-2x text-white position-absolute end-0 bottom-0 m-3 opacity-25"></i>
                                    </div>
                                </a>
                            </div>

                            {{-- Dashboard Card --}}
                            <div class="col-sm-6 col-md-5">
                                <a href="{{ route('dashboard') }}" class="card text-decoration-none glass-card rounded-4">
                                    <div class="card-body p-4 text-start position-relative">
                                        <h4 class="card-title mb-1 text-shadow">Dashboard</h4>
                                        <p class="text-white-75 small">Visualize key metrics and performance data.</p>
                                        <i class="fas fa-chart-bar fa-2x text-white position-absolute end-0 bottom-0 m-3 opacity-25"></i>
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