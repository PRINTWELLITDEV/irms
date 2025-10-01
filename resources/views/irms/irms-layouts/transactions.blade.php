@extends('irms/irms-partials.app')

@section('title', 'IRMS Transactions')

@section('content')

<div class="wrapper">
    <div class="content-wrapper">
        <div class="content-header">
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

        <div class="content-body">
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
                                    <thead class="text-center">
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
                                        @if(auth()->user()->userid === 'sa')
                                            <th width="10%">Site</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    
                                    <tbody>
                                    @forelse($transactions as $trx)
                                        <tr
                                            data-transnum="{{ $trx->trans_num }}"
                                            data-trxdate="{{ $trx->trxdate }}"
                                            data-job="{{ $trx->job }}"
                                            data-item="{{ $trx->item }}"
                                            data-lot="{{ $trx->rslot }}"
                                            data-palletnum="{{ $trx->rspallet_num }}"
                                            data-type="{{ $trx->trxtype }}"
                                            data-qty="{{ ($trx->qty ?? 0) == 0 ? '0' : number_format($trx->qty, 0) }}"
                                            data-um="{{ $trx->um }}"
                                            data-docnum="{{ $trx->docnum }}"
                                            data-rssite="{{ $trx->rssite }}"
                                        >
                                            <td>{{ $trx->trans_num }}</td>
                                            <td>{{ \Carbon\Carbon::parse($trx->trxdate)->format('d M Y') }}</td>
                                            <td>{{ $trx->trxtype }}</td>
                                            <td>{{ $trx->job }}</td>
                                            <td>{{ $trx->item }}</td>
                                            <!-- <td>{{ $trx->rslot }}</td> -->
                                            <td>{{ $trx->rspallet_num }}</td>
                                            <td class="text-end">{{ ($trx->qty ?? 0) == 0 ? '0' : number_format($trx->qty, 0) }}</td>
                                            <td>{{ $trx->um }}</td>
                                            <td>{{ $trx->docnum }}</td>
                                            @if(auth()->user()->userid === 'sa')
                                                <td>{{ $trx->rssite_desc }}</td>
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
