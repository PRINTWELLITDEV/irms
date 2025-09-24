@extends('irms/irms-partials.app')

@section('title', 'IRMS Dispatching')

@section('content')

    <div class="wrapper">
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col mb-3 d-flex align-items-center">
                            <h1 class="d-inline-block mb-0 me-3">Warehouse Goods Dispatching</h1>
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
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 col-lg-9">
                        <div class="card mb-3 shadow-sm">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0">Goods Dispatching Form</h5>
                            </div>
                            <div class="card-body">
                                <!-- Add this style block at the top of your form or in your main CSS -->
                                <style>
                                    .input-group-text.fixed-label {
                                        min-width: 110px;
                                        justify-content: flex-end;
                                    }
                                </style>

                                <form id="goodsDispatchingForm" method="POST" action="{{ route('goodsdispatching.process') }}">
                                    @csrf
                                    <div class="row g-3 align-items-center">
                                        <div class="col-md-6 col-12">
                                            <div class="input-group">
                                                <span class="input-group-text fixed-label" id="date-label">Date:</span>
                                                <input type="date" class="form-control" id="date" name="date" value="{{ date('Y-m-d') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="input-group">
                                                <span class="input-group-text fixed-label" id="whse-label">Warehouse:</span>
                                                <select class="form-select" id="whse" name="whse" required>
                                                    <option value="">Select Warehouse</option>
                                                    <option value="FBIC-B8">FBIC-B8</option>
                                                    <!-- Add more options as needed -->
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3 align-items-center mt-2">
                                        <div class="col-md-6 col-12">
                                            <div class="input-group">
                                                <span class="input-group-text fixed-label" id="jobco-label">Job / CO:</span>
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
                                        <div class="col-md-12 col-12">
                                            <div class="input-group">
                                                <span class="input-group-text fixed-label" id="item-label">Item:</span>
                                                <input type="text" class="form-control" id="item" name="item" readonly>
                                            </div>
                                            <small class="text-muted" id="item-desc"></small>
                                        </div>

                                    </div>
                                    <div class="row g-3 align-items-center mt-2">
                                        <div class="col-md-6 col-12">
                                            <div class="input-group">
                                                <span class="input-group-text fixed-label" id="pallet-size-label">Pallet Size:</span>
                                                <input type="number" class="form-control" id="pallet_size" name="pallet_size">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="input-group">
                                                <span class="input-group-text fixed-label" id="um-label">U/M:</span>
                                                <input type="text" class="form-control" id="um" name="um" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3 align-items-center mt-2">
                                        <div class="col-md-6 col-12">
                                            <div class="input-group">
                                                <span class="input-group-text fixed-label" id="baynum-label">Bay No.:</span>
                                                <select class="form-select" id="baynum" name="baynum">
                                                    <option value="">Select Bay</option>
                                                    <option value="A1">A1</option>
                                                    <!-- Add more options as needed -->
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="input-group">
                                                <span class="input-group-text fixed-label" id="docno-label">Doc No:</span>
                                                <input type="text" class="form-control" id="docno" name="docno">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col text-center">
                                            <button type="submit" class="btn btn-danger px-5">Process</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="dispatching-details" class="card mb-3 shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0">Dispatching Details</h6>
                    </div>
                    <div class="card-body">
                        <button type="button" id="btnBackDispatching" class="btn btn-danger d-flex align-items-center me-2 mb-3">
                            <i class="bi bi-arrow-left d-sm-inline me-2"></i>
                            <span class="d-md-inline">Back</span>
                        </button>
                        <div class="table-responsive bg-secondary bg-opacity-75 p-3 rounded">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th width="5%" class="text-end">Date</th>
                                        <th width="1%">:</th>
                                        <td colspan=4>June 11, 2025</td>
                                    </tr>
                                    <tr>
                                        <th width="5%" class="text-end">Warehouse</th>
                                        <th width="1%">:</th>
                                        <td colspan=4>FBIC-B8</td>
                                    </tr>
                                    <tr>
                                        <th width="5%" class="text-end">Job / CO</th>
                                        <th width="1%">:</th>
                                        <td width="20%">JOB12345</td>
                                        <th width="5%" class="text-end">Lot</th>
                                        <th width="1%">:</th>
                                        <td>LOT12345</td>
                                    </tr>
                                    <tr>
                                        <th class="text-end">Item</th>
                                        <th width="1%">:</th>
                                        <td colspan=4>ITEM001</td>
                                    </tr>
                                    <tr>
                                        <th class="text-end">Bay</th>
                                        <th width="1%">:</th>
                                        <td>BAY001</td>
                                        <th class="text-end">Doc No</th>
                                        <th width="1%">:</th>
                                        <td>DOC12345</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <button type="button" id="btnSelectAll" class="btn btn-outline-dark d-flex align-items-center me-2">
                                <i class="bi bi-check-circle-fill d-sm-inline me-2"></i>
                                <span class="d-md-inline">Select all</span>
                            </button>
                            <button type="button" id="btnDispatch" class="btn btn-success d-flex align-items-center">
                                <i class="bi bi-box-arrow-in-down d-sm-inline me-2"></i>
                                <span class="d-md-inline">Dispatch</span>
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

                            <table id="dispatchingTable" class="table table-striped table-bordered align-middle w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%"></th>
                                        <th width="5%">Select</th>
                                        <th>Rs Loc No.</th>
                                        <th>Pallet Tag No.</th>
                                        <th>Qty to Dispatch</th>
                                        <th>Qty on Hand</th>
                                        <th>U/M</th>
                                        <th>Date Dispatched</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td class="text-center align-middle"><input type="checkbox" name="select_row[]" value="1" class="big-checkbox"></td>
                                        <td>RL-001</td>
                                        <td>PT-1001</td>
                                        <td>50</td>
                                        <td>120</td>
                                        <td>PC</td>
                                        <td>2025-06-11</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td class="text-center align-middle"><input type="checkbox" name="select_row[]" value="2" class="big-checkbox"></td>
                                        <td>RL-002</td>
                                        <td>PT-1002</td>
                                        <td>30</td>
                                        <td>80</td>
                                        <td>PC</td>
                                        <td>2025-06-11</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td class="text-center align-middle"><input type="checkbox" name="select_row[]" value="3" class="big-checkbox"></td>
                                        <td>RL-003</td>
                                        <td>PT-1003</td>
                                        <td>20</td>
                                        <td>60</td>
                                        <td>PC</td>
                                        <td>2025-06-10</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
