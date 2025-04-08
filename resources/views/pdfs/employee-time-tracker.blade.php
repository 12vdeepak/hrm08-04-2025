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
    <h2 class="text-center">{{ $title }}</h2>
    {{-- <h4>Employee Detail</h4>
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
                <p><b>Downloaded : </b>{{ $download_date }}</p>
            </div>
        </div>
    </div> --}}

    <table class="table table-vcenter text-nowrap table-bordered border-bottom dataTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Date</th>
                <th>Project Name</th>
                <th>Job Name</th>
                <th>Work Description</th>
                <th>Time</th>
                <th>Total Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td rowspan="{{ $user['count'] }}">{{ $user['name'] }}</td>

                    @if (count($user['time_trackers']) > 0)
                        @foreach ($user['time_trackers'] as $time_tracker)
                            @php
                                $total_time = '0:0';
                            @endphp

                            <td rowspan="{{ count($time_tracker) }}">
                                {{ date('d-m-Y', strtotime($time_tracker[0]->work_date)) }}</td>
                            <td>{{ $time_tracker[0]->project->name }}</td>
                            <td>{{ $time_tracker[0]->job->name }}</td>
                            <td>{{ $time_tracker[0]->work_title }}</td>
                            <td>{{ $time_tracker[0]->work_time }}</td>
                            @foreach ($time_tracker as $time_tracker_info)
                                @php
                                    [$hours1, $minutes1] = explode(':', $total_time);
                                    if (strpos($time_tracker_info->work_time, ":") !== false){                                
                                    [$hours2, $minutes2] = explode(':', $time_tracker_info->work_time);
                                               }
                                               else{
                                               [$hours2, $minutes2] = explode(':', $total_time);
                                               }      
                                    // Convert hours and minutes to integers
                                    $hours1 = (int) $hours1;
                                    $minutes1 = (int) $minutes1;
                                    $hours2 = (int) $hours2;
                                    $minutes2 = (int) $minutes2;
                                    
                                    // Perform addition for hours and minutes separately
                                    $totalHours = $hours1 + $hours2;
                                    $totalMinutes = $minutes1 + $minutes2;
                                    
                                    // Adjust total hours if minutes exceed 59
                                    if ($totalMinutes >= 60) {
                                        $additionalHours = floor($totalMinutes / 60);
                                        $totalHours += $additionalHours;
                                        $totalMinutes = $totalMinutes % 60;
                                    }
                                    
                                    // Format the result as "HH:MM"
                                    $total_time = sprintf('%02d:%02d', $totalHours, $totalMinutes);
                                @endphp
                            @endforeach
                            <td rowspan="{{ count($time_tracker) }}">{{ $total_time }}</td>
                </tr>
                @foreach ($time_tracker as $time_tracker_info)
                    @if ($loop->iteration !== 1)
                        <tr>
                            <td>{{ $time_tracker_info->project->name }}</td>
                            <td>{{ $time_tracker_info->job->name }}</td>
                            <td>{{ $time_tracker_info->work_title }}</td>
                            <td>{{ $time_tracker_info->work_time }}</td>
                        </tr>
                    @endif
                @endforeach
            @endforeach
        @else
                <td colspan="6" style="text-align: center;">No Time Tracker Record Found</td>
            @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
