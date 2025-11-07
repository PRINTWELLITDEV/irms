@forelse($baylocs as $bay)
    <tr data-rssite="{{ $bay->rssite }}"
        data-rssite_desc="{{ $bay->rssite_desc ?? '' }}"
        data-rsbaynum="{{ $bay->rsbaynum }}"
        data-create-date="{{ $bay->createdate ? \Carbon\Carbon::parse($bay->createdate)->format('d F Y') : '' }}"
        data-createdby="{{ $bay->name ?? '' }}">
        <td>{{ $bay->rsbaynum }}</td>
        @if(auth()->user()->level == 1)
            <td>{{ $bay->rssite_desc ?? 'N/A' }}</td>
        @endif
    </tr>
@empty
@endforelse