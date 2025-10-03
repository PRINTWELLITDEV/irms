@extends('irms/irms-partials.app')

@section('title', 'IRMS Profile')

@section('content')
<main class="app-main">
    <div class="app-content-wrapper">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col mb-3 d-flex align-items-center">
                        <h1 class="d-inline-block mb-0 me-3">Profile</h1>
                        @if(session('success'))
                            <div id="success-alert" class="alert alert-success py-1 px-3 mb-0" style="transition: opacity 0.7s;">
                                {{ session('success') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <div class="card mb-4">
                            <div class="card-body text-center">
                                <img src="{{ $user->profile_pic_url }}" alt="User Avatar" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px;">
                                <h5 class="card-title">{{ $user->name }}</h5>
                                <p class="card-text">{{ $user->email }}</p>
                                <p class="card-text">User ID: {{ $user->userid }}</p>
                                <p class="card-text">Site: <strong>{{ $siteDesc }}</strong></p>
                                <!-- Add more user info as needed -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
