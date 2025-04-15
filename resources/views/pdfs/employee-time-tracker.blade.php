<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Time Tracker Record</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 6px;
            border: 1px solid black;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        /* Ensure consistent column widths across tables */
        .col-name {
            width: 10%;
        }

        .col-date {
            width: 8%;
        }

        .col-project {
            width: 12%;
        }

        .col-job {
            width: 12%;
        }

        .col-desc {
            width: 42%;
        }

        .col-time {
            width: 8%;
        }

        .col-total {
            width: 8%;
        }

        /* Special styling for date headers */
        .date-header {
            background-color: #f9f9f9;
            font-weight: bold;
        }

        /* PDF printing specific styles */
        @media print {
            table {
                page-break-inside: avoid;
            }

            thead {
                display: table-header-group;
            }
        }
    </style>
</head>

<body>
    <h2>{{ $title }}</h2>

    <!-- Master table with headers that will repeat on each page -->
    <table>
        <thead>
            <tr>
                <th class="col-name">Name</th>
                <th class="col-date">Date</th>
                <th class="col-project">Project Name</th>
                <th class="col-job">Job Name</th>
                <th class="col-desc">Work Description</th>
                <th class="col-time">Time</th>
                <th class="col-total">Total Time</th>
            </tr>
        </thead>

        <tbody>
            @php
                $lastUser = null;
                $lastDate = null;
                $dateTotal = null;
            @endphp

            @foreach ($users as $user)
                @if (count($user['time_trackers']) > 0)
                    @foreach ($user['time_trackers'] as $date_index => $time_tracker)
                        @php
                            $currentUser = $user['name'];
                            $currentDate = date('d-m-Y', strtotime($time_tracker[0]->work_date));

                            // Calculate total time for current date
                            $totalHours = 0;
                            $totalMinutes = 0;

                            foreach ($time_tracker as $entry) {
                                if (strpos($entry->work_time, ':') !== false) {
                                    [$h, $m] = explode(':', $entry->work_time);
                                    $totalHours += (int) $h;
                                    $totalMinutes += (int) $m;
                                }
                            }

                            // Adjust if minutes exceed 60
                            if ($totalMinutes >= 60) {
                                $additionalHours = floor($totalMinutes / 60);
                                $totalHours += $additionalHours;
                                $totalMinutes = $totalMinutes % 60;
                            }

                            $dateTotal = sprintf('%02d:%02d', $totalHours, $totalMinutes);
                        @endphp

                        <!-- Start of each date section -->
                        @foreach ($time_tracker as $index => $time_tracker_info)
                            <tr>
                                <!-- Show user name only once per user -->
                                <td>
                                    @if ($lastUser !== $currentUser)
                                        {{ $currentUser }}
                                        @php $lastUser = $currentUser; @endphp
                                    @endif
                                </td>

                                <!-- Show date only once per date -->
                                <td>
                                    @if ($lastDate !== $currentDate || $lastUser !== $currentUser)
                                        {{ $currentDate }}
                                        @php $lastDate = $currentDate; @endphp
                                    @endif
                                </td>

                                <td>{{ $time_tracker_info->project->name }}</td>
                                <td>{{ $time_tracker_info->job->name }}</td>
                                <td>{{ $time_tracker_info->work_title }}</td>
                                <td>{{ $time_tracker_info->work_time }}</td>

                                <!-- Show total only once per date -->
                                <td>
                                    @if ($index === count($time_tracker) - 1)
                                        {{ $dateTotal }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                @else
                    <tr>
                        <td>{{ $user['name'] }}</td>
                        <td colspan="6" style="text-align: center;">No Time Tracker Record Found</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</body>

</html>
