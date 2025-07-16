<h2>Daily Logout Pending Employees Report ({{ now()->format('d M Y') }})</h2>

@if(count($pendingUsers) > 0)
<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Check-in Time</th>
            <th>Check-in Location</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pendingUsers as $user)
            <tr>
                <td>{{ $user->name }} {{ $user->lastname }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->start_time }}</td>
                <td>{{ $user->start_time_location }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@else
    <p>🎉 All employees have logged out today!</p>
@endif 