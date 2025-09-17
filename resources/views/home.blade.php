@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Main Webpage') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <h1>Welcome to IRMS</h1>
                    <p>This is the home page of the IRMS.</p>
                    <a href="{{ url('/login') }}" class="btn btn-primary">
                        Log In
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

