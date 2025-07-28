<h2>Weekly Late Check-In Report</h2>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>Employee Name</th>
            <th>Email</th>
            <th>Date</th>

            <th>Check-In Time</th>

        </tr>
    </thead>
    <tbody>
        @foreach ($data as $entry)
            <tr>
                <td>{{ $entry['name'] }}</td>
                <td>{{ $entry['email'] }}</td>
                <td>{{ \Carbon\Carbon::parse($entry['date'])->format('d M Y') }}</td>

                <td>
                    @if ($entry['checkin'] === 'Did not check in')
                        <span style="color: red;">{{ $entry['checkin'] }}</span>
                    @else
                        {{ \Carbon\Carbon::parse($entry['checkin'])->format('H:i:s') }}
                    @endif
                </td>

            </tr>
        @endforeach
    </tbody>
</table>
