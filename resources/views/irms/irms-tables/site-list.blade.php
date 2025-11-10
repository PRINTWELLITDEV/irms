@foreach($sites as $site)
<tr 
    data-rssite="{{ $site->rssite }}"
    data-rssite_desc="{{ $site->rssite_desc }}"
    data-address="{{ $site->address }}"
    data-site_link="{{ $site->site_link }}"
    data-logo_pic_url="{{ asset($site->logo_pic_url ?? 'uploads/sites-img/no-logo.png') }}"
>
    <td>{{ $site->rssite }}</td>
    <td>{{ $site->rssite_desc }}</td>
    <td class="text-wrap" style="word-break: break-word; max-width: 250px;">
        {{ $site->address }}
    </td>
    <td>
        @if($site->site_link)
            <a href="{{ $site->site_link }}" target="_blank">{{ $site->site_link }}</a>
        @else
            N/A
        @endif
    </td>
    <td>
        <img src="{{ asset($site->logo_pic_url ?? 'uploads/sites-img/no-logo.png') }}" alt="Site Logo" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">   
    </td>
</tr>
@endforeach