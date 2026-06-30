@forelse($transactions as $trx)
    <tr
        data-transnum="{{ $trx->trans_num }}"
        data-trxdate="{{ $trx->trxdate }}"
        data-rsloc="{{ $trx->rsloc }}"
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
        <td>{{ $trx->rsloc }}</td>
        <td>{{ $trx->trxtype }}</td>
        <td>{{ $trx->job }}</td>
        <td>{{ $trx->item }}</td>
        <!-- <td>{{ $trx->rslot }}</td> -->
        <td>{{ $trx->rspallet_num }}</td>
        <td class="text-end">{{ ($trx->qty ?? 0) == 0 ? '0' : number_format($trx->qty, 0) }}</td>
        <td>{{ $trx->um }}</td>
        <td>{{ $trx->docnum }}</td>
        @if(auth()->user()->level == 1)
            <td>{{ $trx->rssite_desc }}</td>
        @endif
    </tr>
@empty
@endforelse