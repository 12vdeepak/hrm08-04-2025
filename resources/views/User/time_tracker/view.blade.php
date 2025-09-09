@extends('layouts.user_app')
@section('content')
    <?php
    error_reporting(0);
    ?>
    <div class="page-header d-xl-flex d-block">

        <div class="page-leftheader">
            <div class="page-title">Time Tracker</div>
        </div>
        <div class="page-rightheader ms-md-auto">
            <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                <div class="btn-list">
                    <a href="{{ route('add_time_tracker_info') }}" class="btn btn-primary me-3">Add</a>
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
                            <div class="row">
                                <div class="col-sm-12 col-md-5">
                                    <div class="dataTables_length" id="hr-table_length">
                                        <label><b>Start Date</b></label>
                                        <input type="date" class="form-control" name="start_date" id="start_date"
                                            value={{ $start_date }}>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-5">
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
                @if (count($time_trackers) > 0)
                    @foreach ($time_trackers as $time_tracker)
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
                                                        @if ($shouldShowProjectDate)
                                                            <th>Project Start Date</th>
                                                            <th>BA Status</th>
                                                        @endif
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
                                                            @if ($shouldShowProjectDate)
                                                                @php
                                                                    $projectStartDate = \App\Models\TimeTracker::where(
                                                                        'project_id',
                                                                        $time_tracker_info->project_id,
                                                                    )
                                                                        ->whereNotNull('project_start_date')
                                                                        ->orderBy('project_start_date', 'asc')
                                                                        ->value('project_start_date');

                                                                    $projectCompleted = !empty($projectStartDate);
                                                                @endphp

                                                                <td>
                                                                    @if ($projectCompleted)
                                                                        <span
                                                                            class="badge badge-success">{{ $projectStartDate }}</span>
                                                                    @else
                                                                        <span class="badge badge-warning">Pending BA</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($projectCompleted)
                                                                        <span class="badge badge-success">Completed</span>
                                                                    @elseif ($time_tracker_info->ba_notified)
                                                                        <span class="badge badge-info">BA Notified</span>
                                                                    @else
                                                                        <span class="badge badge-secondary">Not Sent</span>
                                                                    @endif
                                                                </td>
                                                            @endif

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
                                                            <td><a href="{{ route('edit_time_tracker_info', ['id' => $time_tracker_info]) }}"
                                                                    class="btn btn-warning btn-sm"><i
                                                                        class="feather feather-edit"></i></a>
                                                                <a href="{{ route('delete_time_tracker_info', ['id' => $time_tracker_info]) }}"
                                                                    class="btn btn-danger btn-sm"><i
                                                                        class="feather feather-trash"></i></a>

                                                            </td>
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
            </div>
        </div>
    @endsection
    @section('scripts')
        <script>
            function dateChange() {
                var start_date = document.getElementById("start_date").value;
                var end_date = document.getElementById("end_date").value;
                var url = "{{ route('view_time_tracker_info', ['start_date' => ':start_date', 'end_date' => ':end_date']) }}";
                url = url.replace(':start_date', start_date);
                url = url.replace(':end_date', end_date);
                console.log(url);
                window.location.href = url;
            }
        </script>
    @endsection
