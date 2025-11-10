@forelse ($onlineUsers as $user)
    <tr>
        <td>{{ $user->userid }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->rssite }}</td>
        <td>{{ optional($user->last_seen_at)->diffForHumans() ?? 'N/A' }}</td>
        <td>
            <span class="badge bg-{{ $user->status == 'online' ? 'success' : 'danger' }}">
                {{ ucfirst($user->status) }}
            </span>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center text-info">No active users currently online.</td>
    </tr>
@endforelse
