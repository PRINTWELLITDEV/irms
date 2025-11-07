@forelse($warehouses as $whse)
    <tr data-rssite="{{ $whse->rssite }}"
        data-rssite_desc="{{ $whse->rssite_desc ?? '' }}"
        data-rswhse="{{ $whse->rswhse }}"
        data-name="{{ $whse->name }}"
        data-addr="{{ $whse->addr }}">
        <td class="align-middle">{{ $whse->rswhse }}</td>
        <td class="align-middle">{{ $whse->name }}</td>
        @if(auth()->user()->level == 1)
            <td class="align-middle">{{ $whse->rssite_desc ?? 'N/A' }}</td>
        @endif
    </tr>
@empty
@endforelse