<h2>Weekly Time Tracker Report (Mon–Fri)</h2>

<p>The following employees did not complete 8 hours of work on the dates below:</p>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Date</th>

            <th>Total Time Logged</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($report as $entry)
            <tr>
                <td>{{ $entry['name'] }}</td>
                <td>{{ $entry['email'] }}</td>
                <td>{{ \Carbon\Carbon::parse($entry['date'])->format('d F Y') }}</td>
                <td>{{ $entry['total_time'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
