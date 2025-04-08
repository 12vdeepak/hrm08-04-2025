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
        }

        .employee-details p {
            margin: 0;
        }
 
    </style>

</head>

<body>
    <h3 class="text-center">{{ $title }}</h3>
    <div class="employee-container">
        <div class="employee-details">
            <div class="row">
                <p><b>Month</b> {{ $month }}</p>
            </div>
            <div class="row">
                <p><b>Year</b> {{ $year }}</p>
            </div>
        </div>
    </div>
    <table class="table table-vcenter text-nowrap table-bordered border-bottom ">
        <thead>
            <tr>
                <th class="border-bottom-0">Name</th>
                <th class="border-bottom-0">Email</th>
                <th class="border-bottom-0">Date</th>
                <th class="border-bottom-0">First In</th>
                <th class="border-bottom-0">Last Out</th>
                <th class="border-bottom-0">Time</th>
                <th class="border-bottom-0">Comment</th>
                <th class="border-bottom-0">Status</th>
            </tr>
        </thead>
        <tbody >
            @foreach ($checkins_data as $data)
                <tr>
                    <td rowspan="{{ $days }}">
                        {{ $data['detail']['name'] }}
                    </td>
                    <td rowspan="{{ $days }}">
                        {{ $data['detail']['email'] }}
                    </td>
                        @foreach ($data['record'] as $record)
                            <tr>
                                <td class="text-center">
                                    {{ $record['date'] }}
                                </td>
                                <td class="text-center">
                                    {{ $record['check_in'] }}
                                </td>
                                <td class="text-center">
                                    {{ $record['check_out'] }}
                                </td>
                                <td class="text-center">
                                    {{ $record['time'] }}
                                </td>
                                <td class="text-center">
                                    {{ $record['comment'] }}
                                </td>
                                <td class="text-center">
                                    {{ $record['status'] }}
                                </td>
                            </tr>    
                        @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
