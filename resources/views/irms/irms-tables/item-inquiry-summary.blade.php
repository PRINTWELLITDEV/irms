@forelse($data as $row)
    <tr
        data-warehouse="{{ $row->warehouse }}"
        data-bay="{{ $row->bay_no }}"
        data-status="{{ $row->status }}"
    >        
        <td>{{ $row->warehouse }}</td>
        <td>{{ $row->co }}</td>
        <td>{{ $row->item }}</td>
        <td>{{ $row->item_description }}</td>
        <td>{{ $row->um }}</td>

        <td class="text-end">
            {{ ($row->qty ?? 0) == 0
                ? '0'
                : number_format($row->qty, 0) }}
        </td>
        <td>{{ $row->status }}</td>
        <td>{{ $row->bay_no }}</td>
    </tr>
@empty
@endforelse