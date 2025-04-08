<h2>Daily Attendance Report ({{ now()->format('d M Y') }})</h2>

<h3>🚫 Absent Users</h3>
<ul>
    @forelse($absentUsers as $user)
        <li>{{ $user->name }} {{ $user->lastname }} ({{ $user->email }})</li>
    @empty
        <li>No one is absent today 🎉</li>
    @endforelse
</ul>

<h3>📝 Users on Approved Leave</h3>
<ul>
    @forelse($leaveUsers as $user)
        <li>{{ $user->name }} {{ $user->lastname }} ({{ $user->email }})</li>
    @empty
        <li>No one on leave today</li>
    @endforelse
</ul>
