@extends('irms/irms-partials.app')

@section('title', 'IRMS Item Locations')

@section('content')

<div class="wrapper">
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col mb-3 d-flex align-items-center">
                        <h1 class="d-inline-block mb-0 me-3">Item Locations</h1>
                        @if(session('success'))
                            <div id="success-alert" class="alert alert-success py-1 px-3 mb-0" style="transition: opacity 0.7s;">
                                {{ session('success') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-end align-items-center mb-3">
                                <!-- <button type="button" id="btnAddRack" class="btn btn-success d-flex align-items-center me-2"
                                        data-bs-toggle="modal" data-bs-target="#addRackModal">
                                    <i class="bi bi-plus-circle-fill d-none d-sm-inline me-2"></i>
                                    <span class="d-none d-sm-inline">Add Item Locations</span>
                                    <i class="bi bi-plus-circle-fill d-inline d-sm-none"></i>
                                </button> -->

                                <div class="input-group" style="max-width: 300px;">
                                    <input type="text" id="itemSearch" class="form-control" placeholder="Search Item location...">
                                    <span class="input-group-text">
                                        <i class="bi bi-search"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="table-responsive table-view">
                                <table id="itemloc-table" class="table table-striped table-bordered table-hover align-middle display">
                                    <thead class="text-center">
                                    <tr>
                                        <th>Item Locations</th>
                                        <th>Job No.</th>
                                        <th>Pallet No.</th>
                                        <th>Qty</th>
                                        <th>U/M</th>
                                        @if(auth()->user()->userid === 'sa')
                                            <th width="10%">Site</th>
                                        @endif
                                        <!-- <th width="10%">Create Date</th> -->
                                    </tr>
                                    </thead>
                                    
                                    <tbody>
                                    @forelse($itemlocs as $loc)
                                        <tr
                                            data-rssite="{{ $loc->rssite }}"
                                            data-rssite_desc="{{ $loc->rssite_desc }}"
                                            data-rswhse="{{ $loc->rswhse }}"
                                            data-rsloc="{{ $loc->rsloc }}"
                                            data-pallet_num="{{ $loc->rspallet_num }}"
                                            data-job="{{ $loc->job }}"
                                            data-item="{{ $loc->item }}"
                                            data-desc="{{ $loc->desc }}"
                                            data-qty="{{ ($loc->qty ?? 0) == 0 ? '0' : number_format($loc->qty, 0) }}"
                                            data-um="{{ $loc->um }}"
                                            data-datercvd="{{ $loc->datercvd }}"
                                            data-createdate="{{ $loc->createdate }}"
                                            data-createdby="{{ $loc->createdby }}"
                                        >
                                            <td>{{ $loc->rsloc }}</td>
                                            <td>{{ $loc->job }}</td>
                                            <td>{{ $loc->rspallet_num }}</td>
                                            <td class="text-end">{{ ($loc->qty ?? 0) == 0 ? '0' : number_format($loc->qty, 0) }}</td>
                                            <td>{{ $loc->um }}</td>
                                            @if(auth()->user()->userid === 'sa')
                                                <td>{{ $loc->rssite_desc ?? 'N/A' }}</td>
                                            @endif
                                        </tr>
                                    @empty
                                    @endforelse
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


@endsection
