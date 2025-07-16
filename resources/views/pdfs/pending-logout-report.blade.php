<table>
    <thead>
        <tr>
            <th colspan="6">Pending Logout Report for {{ $date }}</th>
        </tr>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Check-in Time</th>
            <th>Check-in Location</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($records as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }} {{ $user->lastname }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->start_time }}</td>
                <td>{{ $user->start_time_location }}</td>
            </tr>
        @endforeach
    </tbody>
</table> 