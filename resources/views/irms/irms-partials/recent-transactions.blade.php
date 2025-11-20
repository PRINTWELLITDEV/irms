@if(count($recent_transactions) > 0)
    <ul class="activity-list">
        @foreach($recent_transactions as $activity)
            <li class="activity-item">
                <div class="activity-icon {{ $activity['type'] }}">
                    <i class="{{ $activity['icon'] }}"></i>
                </div>
                <div class="activity-content">
                    <h6>{{ $activity['title'] }}</h6>
                    <small>{{ $activity['description'] }} • {{ $activity['time'] }}</small>
                </div>
            </li>
        @endforeach
    </ul>
@else
    <div class="text-center py-4">
        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
        <p class="text-muted">No recent transactions found.</p>
    </div>
@endif