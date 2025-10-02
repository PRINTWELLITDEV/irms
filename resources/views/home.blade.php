@extends('layouts.app')

@section('content')
    {{-- Main wrapper with the background image/gradient for the glass effect --}}
    <div class="glass-bg-container min-vh-100 d-flex align-items-center py-5" id="home-background">
        <div class="container">
            <div class="row justify-content-center w-100">
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

@push('styles')
    <style>
        /* --------------------------------------
                       1. Glassmorphism Setup
           -------------------------------------- */


        .glass-panel {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transform-style: preserve-3d;
        }

        .text-shadow {
            text-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
        }

        /* --------------------------------------
                       2. Glass Cards (Logged-in view)
           -------------------------------------- */
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            color: white !important;
            transition: all 0.3s ease;
            position: relative; /* for absolute positioned icon */
            overflow: hidden;
        }

        .glass-card:hover {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            transform: translateY(-5px);
            text-decoration: none; /* remove underline on hover */
        }

        /* --------------------------------------
                       3. Glass Button (Guest view)
           -------------------------------------- */
        .glass-button {
            background: #007bff;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
        }

        .glass-button:hover {
            background: #0056b3;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
            transform: scale(1.05);
            text-decoration: none;
        }
    </style>
@endpush
