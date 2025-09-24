@extends('irms/irms-partials.app')

@section('title', 'IRMS Receiving')

@section('content')

    <div class="wrapper">
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col mb-3 d-flex align-items-center">
                            <h1 class="d-inline-block mb-0 me-3">Warehouse Goods Receiving</h1>
                            @if(session('success'))
                                <div id="alerts" class="alert alert-success py-1 px-3 mb-0" style="transition: opacity 0.7s;">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if($errors->any())
                                <div id="alerts" class="alert alert-danger py-1 px-3 mb-0" style="transition: opacity 0.7s;">{{ $errors->first() }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div class="row justify-content">
                    <div class="col-12 col-md-8 col-lg-9">
                        <div class="card mb-3 shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Goods Receiving Form</h5>
                            </div>
                            <div class="card-body">
                                <!-- Add this style block at the top of your form or in your main CSS -->
                                <style>
                                    .input-group-text.fixed-label {
                                        min-width: 120px;
                                        justify-content: flex-end;
                                    }
                                </style>

                                @php
                                    $isSa = auth()->user()->userid === 'sa';
                                @endphp

                                <form id="goodsReceivingForm" method="POST" action="{{ route('goodsreceiving.process') }}">
                                    @csrf
                                    @if($isSa)
                                    <div class="row g-3 align-items-center">
                                        <div class="col-md-6 col-12 mb-3">
                                            <div class="input-group">
                                                <span class="input-group-text fixed-label" id="rssite-label">
                                                    <span class="text-danger me-1">*</span>Site:
                                                </span>
                                                <select name="rssite" id="rssite" class="form-select" required>
                                                    <option value="" disabled selected>Select Site</option>
                                                    @foreach($sites as $site)
                                                        <option value="{{ $site->rssite }}" {{ old('rssite') == $site->rssite ? 'selected' : '' }}>
                                                            {{ $site->rssite_desc }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                        <input type="hidden" name="rssite" id="rssite" value="{{ auth()->user()->rssite }}" readonly>
                                    @endif
                                    

                                    <!-- Date -->
                                    <div class="row g-3 align-items-center">
                                        <div class="col-md-6 col-12">
                                            <div class="input-group">
                                                <span class="input-group-text fixed-label" id="date-label">
                                                    <span class="text-danger me-1">*</span>Date:
                                                </span>
                                                <input type="date" class="form-control" id="date" name="date" value="{{ date('Y-m-d') }}" {{ $isSa ? 'disabled' : '' }} required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="input-group">
                                                <span class="input-group-text fixed-label" id="rswhse-label">
                                                    <span class="text-danger me-1">*</span>Warehouse:
                                                </span>
                                                <select name="rswhse" id="rswhse" class="form-select" required>
                                                    <option value=""disabled selected>Select Warehouse</option>
                                                    @foreach($warehouses as $rswhse)
                                                        @if($isSa || $rswhse->rssite === auth()->user()->rssite)
                                                            <option value="{{ $rswhse->rswhse }}" data-site="{{ $rswhse->rssite }}">
                                                                {{ $rswhse->rswhse }} - {{ $rswhse->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                        </div>
                                    </div>

                                    <!-- Job / CO and Lot -->
                                    <div class="row g-3 align-items-center mt-2">
                                        <div class="col-md-6 col-12">
                                            <div class="input-group">
                                                <span class="input-group-text fixed-label" id="jobco-label">
                                                    <span class="text-danger me-1">*</span>Job / CO:
                                                </span>
                                                <input type="text" class="form-control" id="jobco" name="jobco" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="input-group">
                                                <span class="input-group-text fixed-label" id="lot-label">Lot:</span>
                                                <input type="text" class="form-control bg-light" id="lot" name="lot" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3 align-items-center mt-2">
                                        <div class="col-md-6 col-12">
                                            <div class="input-group">
                                                <span class="input-group-text fixed-label" id="item-label">Item:</span>
                                                <input type="text" class="form-control bg-secondary bg-opacity-10" id="item" name="item" readonly>
                                                
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="input-group">
                                                <span class="input-group-text fixed-label" id="desc-label">Description:</span>
                                                <input type="text" class="form-control bg-secondary bg-opacity-10" id="desc" name="desc" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3 align-items-center mt-2">
                                        <div class="col-md-6 col-12">
                                            <div class="input-group">
                                                <span class="input-group-text fixed-label" id="pallet-size-label">Pallet Size:</span>
                                                <input type="number" class="form-control bg-secondary bg-opacity-10" id="pallet_size" name="pallet_size">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="input-group">
                                                <span class="input-group-text fixed-label" id="um-label">U/M:</span>
                                                <input type="text" class="form-control bg-secondary bg-opacity-10" id="um" name="um" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Bay No and Doc No -->
                                    <div class="row g-3 align-items-center mt-2">
                                        <div class="col-md-6 col-12">
                                            <div class="input-group">
                                                <span class="input-group-text fixed-label" id="rsbaynum-label">
                                                    <span class="text-danger me-1">*</span>Bay No.:
                                                </span>
                                                <select name="rsbaynum" id="rsbaynum" class="form-select" required>
                                                    <option value=""disabled selected>Select Bay</option>
                                                    @foreach($baylocs as $bay)
                                                        @if($isSa || $bay->rssite === auth()->user()->rssite)
                                                            <option value="{{ $bay->rsbaynum }}" data-site="{{ $bay->rssite }}">
                                                                {{ $bay->rsbaynum }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="input-group">
                                                <span class="input-group-text fixed-label" id="docno-label">
                                                    Doc No: <small><span class="text-secondary ms-1 small">(Optional)</span></small>
                                                </span>
                                                <input type="text" class="form-control" id="docno" name="docno">
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col text-center">
                                            <button type="submit" class="btn btn-primary px-5">Process</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="receiving-details" class="card mb-3 shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Receiving Details</h5>
                    </div>
                    <div class="card-body">
                        <button type="button" id="btnBackReceiving" class="btn btn-danger d-flex align-items-center mb-2">
                            <i class="bi bi-arrow-left d-sm-inline me-2"></i>
                            <span class="d-md-inline">Back</span>
                        </button>
                        <div class="bg-secondary bg-opacity-50 p-2 mb-3 rounded">
                            <form id="receiving-details-form">
                                <div class="bg-light p-3 rounded shadow-sm">
                                    <div class="row mb-2">
                                        @if(auth()->user()->userid === 'sa')
                                        <div class="col-12">
                                            <span class="fw-bold">Site:</span>
                                            <span id="details-site" class="ms-2"></span>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6">
                                            <span class="fw-bold">Date:</span>
                                            <span id="details-date" class="ms-2"></span>
                                        </div>
                                        <div class="col-6">
                                            <span class="fw-bold">Warehouse:</span>
                                            <span id="details-warehouse" class="ms-2"></span>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6">
                                            <span class="fw-bold">Job / CO:</span>
                                            <span id="details-jobco" class="ms-2"></span>
                                        </div>
                                        <div class="col-6">
                                            <span class="fw-bold">Lot:</span>
                                            <span id="details-lot" class="ms-2"></span>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-12">
                                            <span class="fw-bold">Item:</span>
                                            <span id="details-item" class="ms-2"></span>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-12">
                                            <span class="fw-bold">Description:</span>
                                            <span id="details-description" class="ms-2"></span>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6">
                                            <span class="fw-bold">Pallet Size:</span>
                                            <span id="details-pallet_size" class="ms-2"></span>
                                        </div>
                                        <div class="col-6">
                                            <span class="fw-bold">U/M:</span>
                                            <span id="details-um" class="ms-2"></span>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6">
                                            <span class="fw-bold">Bay No.:</span>
                                            <span id="details-bay" class="ms-2"></span>
                                        </div>
                                        <div class="col-6">
                                            <span class="fw-bold">Doc No:</span>
                                            <span id="details-docno" class="ms-2"></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <button type="button" id="btnSelectAll" class="btn btn-outline-dark d-flex align-items-center me-2">
                                <i class="bi bi-check-circle-fill d-sm-inline me-2"></i>
                                <span class="d-md-inline">Select all</span>
                            </button>
                            <button type="button" id="btnReceive" class="btn btn-success d-flex align-items-center">
                                <i class="bi bi-box-arrow-in-right d-sm-inline me-2"></i>
                                <span class="d-md-inline">Receive</span>
                            </button>
                        </div>
                        <div class="table-responsive">
                            <!-- Add this style block before your table or in your main CSS file -->
                            <style>
                                .table input[type="checkbox"].big-checkbox {
                                    width: 24px;
                                    height: 24px;
                                    accent-color: #174700ff; /* Bootstrap primary */
                                    cursor: pointer;
                                }
                            </style>

                            <table id="receivingTable" class="table table-striped table-bordered align-middle w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th width="2%"></th>
                                        <th width="5%">Select</th>
                                        <th width="25%">Rs Loc No.</th>
                                        <th width="25%">Pallet Tag No.</th>
                                        <th width="10%">Qty to Receive</th>
                                        <th width="10%">Qty on Hand</th>
                                        <th width="5%">U/M</th>
                                        <th width="10%">Date Received</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- JS will populate rows here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.appUrl = "{{ rtrim(config('app.url'), '/') }}";
    </script>
@endsection
