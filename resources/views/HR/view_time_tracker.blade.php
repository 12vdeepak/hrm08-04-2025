@extends('layouts.hr_app')
@section('content')
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <div class="page-title">Time Tracker</div>
        </div>
        <div class="page-rightheader ms-md-auto">
            <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                <div class="btn-list">
                    <a href="{{ route('hr_add_time_tracker_info') }}" class="btn btn-primary me-3">Add</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-xl-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="hr-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="float-end">
                                <div class="col-2">
                                    <a href="{{ route('employee-time-tracker-pdf', ['user_id' => $id, 'start_date' => $start_date, 'end_date' => $end_date]) }}"
                                        class="btn text-white" style="background: black;">Download</a>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-6 col-md-3">
                                    <div id="hr-table_length">
                                        <label style="display:block"><b>Employee</b></label>
                                        <select class="form-control select2" name="id" id="id">
                                            <option value="0">Select Employee</option>
                                            <option value="all">All</option>
                                            @foreach ($employees as $employee)
                                                @if ($employee->id == $id)
                                                    <option value={{ $employee->id }} selected>
                                                        {{ $employee->id }} -
                                                        {{ $employee->name . ' ' . $employee->last_name }}</option>
                                                @else
                                                    <option value={{ $employee->id }}>
                                                        {{ $employee->id }} -
                                                        {{ $employee->name . ' ' . $employee->last_name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-2">
                                    <div class="dataTables_length" id="hr-table_length">
                                        <label><b>Start Date</b></label>
                                        <input type="date" class="form-control" name="start_date" id="start_date"
                                            value={{ $start_date }}>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-2">
                                    <div class="dataTables_length" id="hr-table_length">
                                        <label><b>End Date</b></label>
                                        <input type="date" class="form-control" name="end_date" id="end_date"
                                            value={{ $end_date }}>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-2">
                                    <div class="dataTables_length" id="hr-table_length">
                                        <div class="form-control border-0">
                                            <button class="btn btn-primary mt-5" onclick="dateChange()">Apply</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if (count($data) > 1)
                    @foreach ($data as $d)
                        <div class="row col-sm-12">
                            <h4>ID: {{ $d['id'] }} - Employee Name: {{ $d['name'] }}</h4>
                            <hr>
                        </div>
                        @if (count($d['time_trackers']) > 0)
                            @foreach ($d['time_trackers'] as $time_tracker)
                                @php
                                    $total_time = '0:0';
                                @endphp
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <div id="hr-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                            <div class="row">

                                                <div class="col-sm-12">
                                                    <h4>Date : {{ date('d-m-y', strtotime($time_tracker[0]->work_date)) }}
                                                    </h4>
                                                </div>
                                                <div class="col-sm-12">
                                                    <table
                                                        class="table table-vcenter text-nowrap table-bordered border-bottom dataTable no-footer"
                                                        id="hr-table" role="grid" aria-describedby="hr-table_info">
                                                        <thead>
                                                            <tr>
                                                                <th>SNo.</th>
                                                                <th>Project Name</th>
                                                                <th>Job Name</th>
                                                                <th>Work Description</th>
                                                                <th>Time</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            @foreach ($time_tracker as $time_tracker_info)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $time_tracker_info->project->name }}</td>
                                                                    <td>{{ $time_tracker_info->job->name }}</td>
                                                                    <td>{{ $time_tracker_info->work_title }}</td>
                                                                    <td>{{ $time_tracker_info->work_time }}</td>
                                                                    @php
                                                                        [$hours1, $minutes1] = explode(
                                                                            ':',
                                                                            $total_time,
                                                                        );
                                                                        if (
                                                                            strpos(
                                                                                $time_tracker_info->work_time,
                                                                                ':',
                                                                            ) !== false
                                                                        ) {
                                                                            [$hours2, $minutes2] = explode(
                                                                                ':',
                                                                                $time_tracker_info->work_time,
                                                                            );
                                                                        } else {
                                                                            [$hours2, $minutes2] = explode(
                                                                                ':',
                                                                                $total_time,
                                                                            );
                                                                        } // Convert hours and minutes to integers
                                                                        $hours1 = (int) $hours1;
                                                                        $minutes1 = (int) $minutes1;
                                                                        $hours2 = (int) $hours2;
                                                                        $minutes2 = (int) $minutes2;

                                                                        // Perform addition for hours and minutes separately
                                                                        $totalHours = $hours1 + $hours2;
                                                                        $totalMinutes = $minutes1 + $minutes2;

                                                                        // Adjust total hours if minutes exceed 59
                                                                        if ($totalMinutes >= 60) {
                                                                            $additionalHours = floor(
                                                                                $totalMinutes / 60,
                                                                            );
                                                                            $totalHours += $additionalHours;
                                                                            $totalMinutes = $totalMinutes % 60;
                                                                        }

                                                                        // Format the result as "HH:MM"
                                                                        $total_time = sprintf(
                                                                            '%02d:%02d',
                                                                            $totalHours,
                                                                            $totalMinutes,
                                                                        );
                                                                    @endphp
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    <div class="col-sm-12" align="right">
                                                        <h4>Total Time : {{ $total_time . ' Hrs' }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="card-body">
                                <div class="table-responsive">
                                    <div id="hr-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                        <div class="row">
                                            <div class="col-sm-12 text-center">
                                                <h5>No Data Found!</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                    @if (count($data['time_trackers']) > 0)
                        <div class="row col-sm-12">
                            <h4>ID: {{ $id }} - Employee Name: {{ $name }}</h4>
                            <hr>
                        </div>
                        @foreach ($data['time_trackers'] as $time_tracker)
                            @php
                                $total_time = '0:0';
                            @endphp
                            <div class="card-body">
                                <div class="table-responsive">
                                    <div id="hr-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                        <div class="row">

                                            <div class="col-sm-12">
                                                <h4>Date : {{ date('d-m-y', strtotime($time_tracker[0]->work_date)) }}</h4>
                                            </div>
                                            <div class="col-sm-12">
                                                <table
                                                    class="table table-vcenter text-nowrap table-bordered border-bottom dataTable no-footer"
                                                    id="hr-table" role="grid" aria-describedby="hr-table_info">
                                                    <thead>
                                                        <tr>
                                                            <th>SNo.</th>
                                                            <th>Project Name</th>
                                                            <th>Job Name</th>
                                                            <th>Work Description</th>
                                                            <th>Time</th>
                                                            <th>Action</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($time_tracker as $time_tracker_info)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $time_tracker_info->project->name }}</td>
                                                                <td>{{ $time_tracker_info->job->name }}</td>
                                                                <td>{{ $time_tracker_info->work_title }}</td>
                                                                <td>{{ $time_tracker_info->work_time }}</td>
                                                                @php
                                                                    [$hours1, $minutes1] = explode(':', $total_time);
                                                                    [$hours2, $minutes2] = explode(
                                                                        ':',
                                                                        $time_tracker_info->work_time,
                                                                    );

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
                                                                    $total_time = sprintf(
                                                                        '%02d:%02d',
                                                                        $totalHours,
                                                                        $totalMinutes,
                                                                    );
                                                                @endphp
                                                                @if ($time_tracker_info->user_id == auth()->user()->id)
                                                                    <td>

                                                                        <a href="{{ route('edit_hr_time_tracker_info', ['id' => $time_tracker_info]) }}"
                                                                            class="btn btn-warning btn-sm"><i
                                                                                class="feather feather-edit"></i></a>
                                                                        <a href="{{ route('hr_delete_time_tracker', ['id' => $time_tracker_info]) }}"
                                                                            class="btn btn-danger btn-sm"><i
                                                                                class="feather feather-trash"></i></a>
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <div class="col-sm-12" align="right">
                                                    <h4>Total Time : {{ $total_time . ' Hrs' }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="card-body">
                            <div class="table-responsive">
                                <div id="hr-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h5>No Data Found!</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
        @if (count($data) > 1)
            {{ $employees->links() }}
        @endif
    @endsection
    @section('scripts')
        <script>
            $(document).ready(() => {
                $('.select2').select2();
            });

            function dateChange() {
                var start_date = document.getElementById("start_date").value;
                var end_date = document.getElementById("end_date").value;
                var id = document.getElementById("id").value;
                var url =
                    "{{ route('view_employee_time_tracker', ['id' => ':id', 'start_date' => ':start_date', 'end_date' => ':end_date']) }}";
                url = url.replace(':id', id);
                url = url.replace(':start_date', start_date);
                url = url.replace(':end_date', end_date);
                console.log(url);
                window.location.href = url;
            }
        </script>
    @endsection
