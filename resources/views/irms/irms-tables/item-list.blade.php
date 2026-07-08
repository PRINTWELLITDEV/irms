@foreach($itemlocs as $loc)
    <tr 
        data-rspallet_num="{{ $loc->rspallet_num }}"
        data-rsloc="{{ $loc->rsloc }}"
        data-job="{{ $loc->job }}" data-rssite="{{ $loc->rssite }}" data-rssite-desc="{{ $loc->rssite_desc }}"
        data-item="{{ $loc->item }}" data-desc="{{ $loc->desc }}"
        data-totalqty="{{ ($loc->totalqty ?? 0) == 0 ? '0' : number_format($loc->totalqty, 0) }}" data-um="{{ $loc->um }}"
        data-rswhse="{{ $loc->rswhse }}">
        <td>{{ $loc->rswhse }}</td>
        <td>{{ $loc->rsloc }}</td>
        <td>{{ $loc->job }}</td>
        <td>
            <div class="fw-semibold">{{ $loc->item }}</div>
            <div class="small text-muted">{{ $loc->desc }}</div>
        </td>
        <td>{{ $loc->rspallet_num }}</td>
        <td class="text-end">{{ ($loc->totalqty ?? 0) == 0 ? '0' : number_format($loc->totalqty, 0) }} {{ $loc->um }}</td>
        @if(auth()->user()->level == 1)
            <td>{{ $loc->rssite_desc }}</td>
        @endif
    </tr>
@endforeach

    <!-- onclick="window.location='{{ route('itemloc.showJobDetails', ['job' => $loc->job]) }}'"
        style="cursor:pointer;" -->