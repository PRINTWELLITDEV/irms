{{-- resources/views/irms/irms-partials/active_users_table_body.blade.php --}}
@forelse ($onlineUsers as $user)
    <tr id="user-row-{{ $user->userid }}">
        <td>{{ $user->userid }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->rssite }}</td>
        {{-- Convert to a more human-friendly time format --}}
        <td>{{ \Carbon\Carbon::parse($user->last_seen_at)->diffForHumans() }}</td>
        <td><span class="badge bg-{{ $user->status === 'online' ? 'success' : 'danger' }}">{{ ucfirst($user->status) }}</span></td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center text-muted">No active users logged in right now.</td>
    </tr>
@endforelse
