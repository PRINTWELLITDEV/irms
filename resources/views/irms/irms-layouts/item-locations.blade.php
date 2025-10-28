@extends('irms/irms-partials.app')

@section('title', 'IRMS Item Locations')

@section('content')

<main class="app-main">
    <div class="app-content-wrapper">
        <div class="app-content-header">
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

        <div class="app-content">
            <div class="container-fluid">
                <div class="row" id="item-list-view">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-end align-items-center mb-3">
                                    <div class="input-group" style="max-width: 300px;">
                                        <input type="text" id="itemSearch" class="form-control" placeholder="Search Item location...">
                                        <span class="input-group-text">
                                            <i class="bi bi-search"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="table-responsive table-view">
                                    <table id="itemloc-table" class="table table-striped table-hover align-middle display">
                                        <thead>
                                        <tr>
                                            <th width="10%">Job No.</th>
                                            <th width="30%">Product Item</th>
                                            <th width="10%">Quantity</th>
                                            <!-- <th width="5%">U/M</th> -->
                                            <th width="10%">Warehouse</th>
                                            @if(auth()->user()->level == 1)
                                                <th width="10%">Site</th>
                                            @endif
                                        </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @foreach($itemlocs as $loc)
                                                <tr onclick="window.location='{{ route('itemloc.showJobDetails', ['job' => $loc->job]) }}'"
                                                    style="cursor:pointer;"
                                                    data-job="{{ $loc->job }}" data-rssite="{{ $loc->rssite }}" data-rssite-desc="{{ $loc->rssite_desc }}"
                                                    data-item="{{ $loc->item }}" data-desc="{{ $loc->desc }}"
                                                    data-totalqty="{{ ($loc->totalqty ?? 0) == 0 ? '0' : number_format($loc->totalqty, 0) }}" data-um="{{ $loc->um }}"
                                                    data-rswhse="{{ $loc->rswhse }}">
                                                    <td>{{ $loc->job }}</td>
                                                    <td>
                                                        <div class="fw-semibold">{{ $loc->item }}</div>
                                                        <div class="small text-muted">{{ $loc->desc }}</div>
                                                    </td>
                                                    <td class="text-end">{{ ($loc->totalqty ?? 0) == 0 ? '0' : number_format($loc->totalqty, 0) }} {{ $loc->um }}</td>
                                                    <td>{{ $loc->rswhse }}</td>
                                                    @if(auth()->user()->level == 1)
                                                        <td>{{ $loc->rssite_desc }}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
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
