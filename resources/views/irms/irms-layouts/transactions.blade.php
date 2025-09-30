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
                                        <th>Transaction No.</th>
                                        <th>Transaction Date</th>
                                        @if(auth()->user()->userid === 'sa')
                                            <th width="10%">Site</th>
                                        @endif
                                        <th>Job</th>
                                        
                                        <th>Item</th>
                                        <th>Lot</th>
                                        <th>Type</th>
                                        <th>Qty</th>
                                        <th>U/M</th>
                                        <!-- <th width="10%">Create Date</th> -->
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
                                            data-type="{{ $trx->trxtype }}"
                                            data-qty="{{ ($trx->qty ?? 0) == 0 ? '0' : number_format($trx->qty, 0) }}"
                                            data-um="{{ $trx->um }}"
                                            @if(auth()->user()->userid === 'sa')
                                                data-rssite_desc="{{ $trx->rssite_desc }}"
                                            @endif
                                        >
                                            <td>{{ $trx->trans_num }}</td>
                                            <td>{{ \Carbon\Carbon::parse($trx->trxdate)->format('d M Y') }}</td>
                                            @if(auth()->user()->userid === 'sa')
                                                <td>{{ $trx->rssite_desc }}</td>
                                            @endif
                                            <td>{{ $trx->job }}</td>
                                            <td>{{ $trx->item }}</td>
                                            <td>{{ $trx->rslot }}</td>
                                            <td>{{ $trx->trxtype }}</td>
                                            <td class="text-end">{{ ($trx->qty ?? 0) == 0 ? '0' : number_format($trx->qty, 0) }}</td>
                                            <td>{{ $trx->um }}</td>
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
