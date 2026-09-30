@forelse($data as $row)
    <tr>
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

        <td>
            @if($row->date_received)
                {{ \Carbon\Carbon::parse($row->date_received)->format('M d, Y') }}
            @endif
        </td>
        <td>{{ $row->location }}</td>

    </tr>
@empty

@endforelse