@extends('irms/irms-partials.app')

@section('title', 'IRMS Job Locations')

@section('content')

<main class="app-main">
    <div class="app-content-wrapper">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col mb-3 d-flex align-items-center">
                        <h1 class="d-inline-block mb-0 me-3">Job Locations</h1>
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
                <div class="row" id="job-list-view">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-end align-items-center mb-3">
                                    <div class="input-group" style="max-width: 300px;">
                                        <input type="text" id="jobSearch" class="form-control" placeholder="Search Job location...">
                                        <span class="input-group-text">
                                            <i class="bi bi-search"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="table-responsive table-view">
                                    <table id="jobloc-table" class="table table-striped table-hover align-middle display">
                                        <thead>
                                        <tr>
                                            <th width="10%">Warehouse</th>
                                            <th width="10%">Job No.</th>
                                            <th width="30%">Product Item</th>
                                            <th width="10%">Quantity</th>
                                            <!-- <th width="5%">U/M</th> -->
                                            
                                            @if(auth()->user()->level == 1)
                                                <th width="10%">Site</th>
                                            @endif
                                        </tr>
                                        </thead>
                                        
                                        <tbody>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>



@endsection

@push('scripts')
    @vite('resources/js/irms/rsjobloc.js')
@endpush
