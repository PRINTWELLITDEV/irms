@extends('irms/irms-partials.app')

@section('title', 'IRMS Dashboard')

@section('content')

    <div class="wrapper">
        @include('irms.irms-partials.aside')
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            @include('irms.irms-partials.nav')
            <div class="content-header">
                <h1>Welcome to IRMS Dashboard</h1>
            </div>
            <div class="content-body">
                <p>This is the main content of the IRMS.</p>
            </div>
        </div>

    </div>

@endsection


