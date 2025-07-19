@php
    $reportRows = $reportRows ?? [];
    $reportMessage = $reportMessage ?? '';
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Weekly Login Compliance Report</title>
    <style>
        body { font-family: Arial, sans-serif; color: #222; }
        .container { max-width: 700px; margin: 0 auto; padding: 20px; }
        h2 { color: #2a5d84; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Weekly Login Compliance Report</h2>
        <p>Dear HR,</p>
        <p>{!! $reportMessage !!}</p>
        <table>
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Date</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportRows as $row)
                    <tr>
                        <td>{{ $row['name'] }}</td>
                        <td>{{ $row['date'] }}</td>
                        <td>{{ $row['check_in'] }}</td>
                        <td>{{ $row['check_out'] }}</td>
                        <td>{{ $row['remarks'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p style="margin-top: 30px;">Regards,<br>HRMS System</p>
    </div>
</body>
</html> 