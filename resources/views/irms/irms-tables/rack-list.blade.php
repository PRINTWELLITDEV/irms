@foreach($racklocs as $rack)
    <tr data-rssite="{{ $rack->rssite }}"
        data-rssite_desc="{{ $rack->rssite_desc ?? '' }}"
        data-rswhse="{{ $rack->rswhse }}"
        data-rsbaynum="{{ $rack->rsbaynum }}"
        data-rsloc="{{ $rack->rsloc }}"
        data-rsdesc="{{ $rack->rsdesc ?? '' }}"
        data-qty="{{ $rack->qty ?? 0 }}"
        data-create-date="{{ $rack->createdate ? \Carbon\Carbon::parse($rack->createdate)->format('d F Y') : '' }}">
        <td>{{ $rack->rsloc }}</td>
        <td>{{ $rack->rswhse }}</td>
        <td>{{ $rack->rsbaynum }}</td>
        <td>{{ number_format($rack->qty ?? 0, 0) }}</td>
        @if(auth()->user()->level == 1)
            <td>{{ $rack->rssite_desc ?? 'N/A' }}</td>
        @endif
    </tr>
@endforeach
