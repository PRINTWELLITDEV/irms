@extends('irms/irms-partials.app')

@section('title', 'IRMS Transactions')

@section('content')

<main class="app-main">
    <div class="app-content-wrapper">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col mb-3 d-flex align-items-center">
                        <h1 class="d-inline-block mb-0 me-3">Transactions</h1>
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
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-end align-items-center mb-3">
                                    <div class="input-group" style="max-width: 300px;">
                                        <input type="text" id="transSearch" class="form-control" placeholder="Search transaction...">
                                        <span class="input-group-text">
                                            <i class="bi bi-search"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="table-responsive table-view">
                                    <table id="transaction-table" class="table table-striped table-bordered table-hover align-middle display">
                                        <thead>
                                        <tr>
                                            <th width="5%">Trans No.</th>
                                            <th width="5%">Trans Date</th>
                                            <th width="5%">Type</th>
                                            <th width="">Job</th>
                                            <th width="">Item</th>
                                            <!-- <th>Lot</th> -->
                                            <th width="5%">Pallet No.</th>
                                            <th width="5%">Qty</th>
                                            <th width="5%">U/M</th>
                                            <th width="5%">Doc No.</th>
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
