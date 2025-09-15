@extends('irms/irms-partials.app')

@section('title', 'IRMS Bay Location')

@section('content')

    <div class="wrapper">
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="content-header">
                <h1>Bay Location</h1>
            </div>
            <div class="content-body">
                <div class="row">
                    <div class="col">
                        <div class="card mx-auto">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <button type="button" id="btnAddWarehouse" class="btn btn-success d-flex align-items-center"
                                        data-bs-toggle="modal" data-bs-target="#addWarehouseModal">
                                        <i class="bi bi-plus-circle-fill d-none d-sm-inline me-2"></i>
                                        <span class="d-none d-sm-inline">Add Bay</span>
                                        <i class="bi bi-plus-circle-fill d-inline d-sm-none"></i>
                                    </button>
                                    <div class="input-group" style="max-width: 300px;">
                                        <input type="text" id="baylocSearch" class="form-control"
                                            placeholder="Search bay location...">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="bayloc-table" class="table table-striped table-bordered table-hover align-middle">
                                        <thead class="table-dark text-center">
                                            <tr>
                                                <th width="5%">Site</th>
                                                <th>Bay Number</th>
                                                <th>Created Date</th>
                                                <th width="10%">Created By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($baylocs as $bay)
                                                <tr>
                                                    <td class="text-center align-middle">
                                                        @if(!empty($bay->logo_pic_url))
                                                            <img src="{{ asset($bay->logo_pic_url) }}" alt="logo" class="mx-auto d-block" width="40" height="40" style="object-fit:contain;vertical-align:middle;">
                                                        @endif
                                                    </td>
                                                    <td>{{ $bay->rsbaynum }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($bay->createdate)->format('d-M-Y h:i A') }}</td>
                                                    <td>{{ $bay->createdby }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">No bay locations found</td>
                                                </tr>
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
