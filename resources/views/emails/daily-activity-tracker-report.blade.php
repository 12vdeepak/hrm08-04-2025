<!DOCTYPE html>
<html>

<head>
    <title>Daily Activity Tracker Report</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-family: Arial, sans-serif;
        }

        th,
        td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f3f3f3;
        }
    </style>
</head>

<body>
    <p>{{ $body }}</p>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Date</th>
                <th>Worked Hours</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reportRows as $row)
                <tr>
                    <td>{{ $row['name'] }}</td>
                    <td>{{ $row['date'] }}</td>
                    <td>{{ $row['worked_hours'] }}</td>
                    <td>{{ $row['status'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
