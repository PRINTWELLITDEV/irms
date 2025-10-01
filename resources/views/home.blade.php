@extends('layouts.app')

@section('content')
    <div class="container min-vh-100 d-flex align-items-center">
        <div class="row justify-content-center w-100 h-100">
            <div class="col-md-8 text-center">
                <h1 class="display-4 mb-4">IRMS</h1>
                <p class="lead text-muted mb-5">Inventory Racking Management System</p>

                @guest
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5">
                        Login
                    </a>
                @else
                    <div class="row g-4 mt-3">
                        <div class="col-sm-6">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Inventory</h5>
                                    <p class="text-muted">Manage your inventory</p>
                                    <a href="" class="stretched-link"></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Dashboard</h5>
                                    <p class="text-muted">View analytics</p>
                                    <a href="" class="stretched-link"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .card {
                transition: transform 0.2s ease;
            }

            .card:hover {
                transform: translateY(-5px);
            }
        </style>
    @endpush
@endsection
