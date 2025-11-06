@forelse($users as $user)
    @php
        $profile_pic_url = $user->profile_pic_url ?? 'uploads/user-profile/noprofile.png';
        if (!file_exists(public_path($profile_pic_url)) || !$profile_pic_url) {
            $profile_pic_url = 'uploads/user-profile/noprofile.png';
        }
    @endphp

    <tr data-userid="{{ $user->userid }}" data-name="{{ $user->name }}"
        data-email="{{ $user->email }}" data-site="{{ $user->rssite }}"
        data-department="{{ $user->department }}" data-section="{{ $user->section }}"
        data-position="{{ $user->position }}"
        data-site_desc="{{ $user->rssite_desc }}" data-level="{{ $user->level }}"
        data-gender="{{ $user->gender }}"
        data-profile="{{ asset($profile_pic_url) }}"
        data-create_date="{{ date('d F Y', strtotime($user->create_date)) }}">
        <td class="align-middle">
            <img src="{{ asset($profile_pic_url) }}" alt="profile" class="rounded-circle border border-3">
            {{ $user->name }}
        </td>
        <td class="align-middle">{{ $user->userid }}</td>
        <td class="align-middle">
            {{ $user->rssite_desc ?? 'N/A' }}
        </td>
        <td class="align-middle text-center">{{ $user->level }}</td>
    </tr>
@empty
@endforelse