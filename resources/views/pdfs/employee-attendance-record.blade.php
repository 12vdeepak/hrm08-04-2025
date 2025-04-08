<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            border: 1px solid black;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }

        .text-nowrap {
            white-space: nowrap;
        }

        .border-bottom {
            border-bottom: 1px solid black;
        }

        .no-footer {
            border-bottom: none;
        }

        .employee-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3rem;
        }

        .employee-details {
            flex-basis: 50%;
        }

        .employee-details b {
            font-weight: bold;
            font-size: 15px;
        }

        .employee-details p {
            margin: 0;
            font-size: 13px;
        }
    </style>
</head>

<body>
    <h2 class="text-center">{{ $title }}</h2>
    <h4>Employee Detail</h4>
    <div class="employee-container">
        <div class="employee-details">
            <div class="row">
                <p><b>Employee Name : </b>{{ $employee->name . ' ' . $employee->lastname }}</p>
            </div>
            <div class="row">
                <p><b>Phone : </b>{{ $employee->phone ?? '-' }}</p>
            </div>
            <div class="row">
                <p><b>Email : </b>{{ $employee->email }}</p>
            </div>
        </div>
        <div class="employee-details">
            <div class="row">
                <p><b>Downloaded : </b>{{ $date }}</p>
            </div>
        </div>
    </div>

    <table class="table table-vcenter text-nowrap table-bordered border-bottom dataTable no-footer" id="hr-table"
        role="grid" aria-describedby="hr-table_info">
        <thead>
            <tr>
                <th>Date</th>
                <th>First In</th>
                <th>Location(First In)</th>
                <th>Last Out</th>
                <th>Location(Last Out)</th>
                <th>Time</th>
                <th>Comment</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $employee_attendance)
                <tr>
                    <td class="text-nowrap">{{ date('d-m-Y', strtotime($employee_attendance['date'])) }}</td>
                    <td>{{ $employee_attendance['check_in'] }}</td>
                    <td>{{ $employee_attendance['check_in_location'] }}</td>
                    <td>{{ $employee_attendance['check_out'] }}</td>
                    <td>{{ $employee_attendance['check_out_location']}}</td>
                    <td>{{ $employee_attendance['time'] }}</td>
                    <td>{{ $employee_attendance['comment'] }}</td>
                    <td>{{ $employee_attendance['status'] }}</td>
                <tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
