@foreach($racklocs as $rack)
    <tr data-rssite="{{ $rack->rssite }}"
        data-rssite_desc="{{ $rack->rssite_desc ?? '' }}"
        data-rswhse="{{ $rack->rswhse }}"
        data-rsbaynum="{{ $rack->rsbaynum }}"
        data-rsloc="{{ $rack->rsloc }}"
        data-rsdesc="{{ $rack->rsdesc ?? '' }}"
        data-qty="{{ $rack->qty ?? 0 }}"
        data-create-date="{{ $rack->createdate ? \Carbon\Carbon::parse($rack->createdate)->format('d F Y') : '' }}"
        data-isquarantine="{{ $rack->isQuarantine }}">
        <td>{{ $rack->rsloc }}</td>
        <td>{{ $rack->rswhse }}</td>
        <td>{{ $rack->rsbaynum }}</td>
        <td class="text-end">{{ number_format($rack->qty ?? 0, 0) }}</td>
        <td style="font-size:19.5px; padding-top:5px;">
        @if($rack->isQuarantine == 1)
            <span class="badge bg-danger fw-normal">Quarantined</span>
        @else
            <span class="badge bg-success fw-normal">Not Quarantine</span>
        @endif
        </td>
        @if(auth()->user()->level == 1)
            <td>{{ $rack->rssite_desc ?? 'N/A' }}</td>
        @endif
        
    </tr>
@endforeach