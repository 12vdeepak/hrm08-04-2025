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
                                                                    <td>{{ $time_tracker_info->project?->name ?? 'N/A' }}
                                                                    </td>
                                                                    <td>{{ $time_tracker_info->job?->name ?? 'N/A' }}</td>
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
                                                            @if (isset($showProjectStartDate) && $showProjectStartDate)
                                                                <th>Project Start Date</th>
                                                                <th>Project End Date</th>
                                                            @endif
                                                            @if (isset($showProjectStartDate) && $showProjectStartDate)
                                                                <th>Project Type</th>
                                                            @endif
                                                            <th>Job Name</th>
                                                            <th>Work Description</th>
                                                            <th>Time</th>
                                                            @if (isset($showProjectStartDate) && $showProjectStartDate)
                                                                <th>Overdue Status</th>
                                                                <th>Reason & HR Action</th>
                                                            @endif
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $shownProjects = []; @endphp
                                                        @foreach ($time_tracker as $time_tracker_info)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $time_tracker_info->project?->name ?? 'N/A' }}</td>
                                                                @if (isset($showProjectStartDate) && $showProjectStartDate)
                                                                    <td>
                                                                        {{ $time_tracker_info->project_start_date
                                                                            ? date('d-m-Y', strtotime($time_tracker_info->project_start_date))
                                                                            : 'N/A' }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $time_tracker_info->project_end_date
                                                                            ? date('d-m-Y', strtotime($time_tracker_info->project_end_date))
                                                                            : 'N/A' }}
                                                                    </td>
                                                                @endif
                                                                @if (isset($showProjectStartDate) && $showProjectStartDate)
                                                                    <td>
                                                                        {{ $time_tracker_info->project_type ? $time_tracker_info->project_type : 'N/A' }}
                                                                    </td>
                                                                @endif
                                                                <td>{{ $time_tracker_info->job?->name ?? 'N/A' }}</td>
                                                                <td>
                                                                    {{ Str::limit($time_tracker_info->work_title, 40) }}
                                                                    @if (strlen($time_tracker_info->work_title) > 40)
                                                                        <a href="javascript:void(0);" class="text-primary ms-1 view-full-text" 
                                                                           data-title="Work Description" 
                                                                           data-content="{{ $time_tracker_info->work_title }}">
                                                                           <i class="feather feather-eye"></i>
                                                                        </a>
                                                                    @endif
                                                                </td>
                                                                <td>{{ $time_tracker_info->work_time }}</td>
                                                                @if (isset($showProjectStartDate) && $showProjectStartDate)
                                                                    <td>
                                                                        @if ($time_tracker_info->project_status === 'completed')
                                                                            <span class="badge bg-success">Completed</span>
                                                                        @elseif($time_tracker_info->project_status === 'in_progress')
                                                                            <span class="badge bg-warning">In Progress</span>
                                                                        @else
                                                                            <span class="badge bg-secondary">N/A</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if ($time_tracker_info->project_status === 'in_progress')
                                                                            <p class="mb-1">
                                                                                <strong>Dev Reason:</strong>
                                                                                {{ Str::limit($time_tracker_info->status_reason, 30) }}
                                                                                @if (strlen($time_tracker_info->status_reason) > 30)
                                                                                    <a href="javascript:void(0);" class="text-primary ms-1 view-full-text" 
                                                                                       data-title="Dev Reason" 
                                                                                       data-content="{{ $time_tracker_info->status_reason }}">
                                                                                       <i class="feather feather-info"></i>
                                                                                    </a>
                                                                                @endif
                                                                            </p>
                                                                            @if ($time_tracker_info->ba_delay_reason)
                                                                                <p class="mb-1 text-info">
                                                                                    <strong>BA Reason:</strong>
                                                                                    {{ Str::limit($time_tracker_info->ba_delay_reason, 30) }}
                                                                                    @if (strlen($time_tracker_info->ba_delay_reason) > 30)
                                                                                        <a href="javascript:void(0);" class="text-info ms-1 view-full-text" 
                                                                                           data-title="BA Reason" 
                                                                                           data-content="{{ $time_tracker_info->ba_delay_reason }}">
                                                                                           <i class="feather feather-info"></i>
                                                                                        </a>
                                                                                    @endif
                                                                                </p>
                                                                            @endif
                                                                            <div class="hr-action mt-1">
                                                                                @if ($time_tracker_info->hr_status === 'pending')
                                                                                    @if (!in_array($time_tracker_info->project_id, $shownProjects))
                                                                                        <form
                                                                                            action="{{ route('hr.approve.project.reason', $time_tracker_info->id) }}"
                                                                                            method="POST"
                                                                                            class="d-inline">
                                                                                            @csrf
                                                                                            <button type="submit"
                                                                                                class="btn btn-success btn-xs"
                                                                                                title="Approve & Notify BA (Bulk)">Approve</button>
                                                                                        </form>
                                                                                        <form
                                                                                            action="{{ route('hr.reject.project.reason', $time_tracker_info->id) }}"
                                                                                            method="POST"
                                                                                            class="d-inline">
                                                                                            @csrf
                                                                                            <button type="submit"
                                                                                                class="btn btn-danger btn-xs"
                                                                                                title="Reject Reason (Bulk)">Reject</button>
                                                                                        </form>
                                                                                        @php $shownProjects[] = $time_tracker_info->project_id; @endphp
                                                                                    @else
                                                                                        <span class="text-muted small">Pending
                                                                                            (Apply to first entry)</span>
                                                                                    @endif
                                                                                @elseif($time_tracker_info->hr_status === 'approved')
                                                                                    <span class="text-success small">Approved
                                                                                        & Mail Sent</span>
                                                                                @elseif($time_tracker_info->hr_status === 'rejected')
                                                                                    <span class="text-danger small">Rejected</span>
                                                                                @endif
                                                                            </div>
                                                                        @else
                                                                            -
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
                                                                <td>
                                                                    @if ($time_tracker_info->user_id == auth()->user()->id)
                                                                        <a href="{{ route('edit_hr_time_tracker_info', ['id' => $time_tracker_info]) }}"
                                                                            class="btn btn-warning btn-sm"><i
                                                                                class="feather feather-edit"></i></a>
                                                                        <a href="{{ route('hr_delete_time_tracker', ['id' => $time_tracker_info]) }}"
                                                                            class="btn btn-danger btn-sm"><i
                                                                                class="feather feather-trash"></i></a>
                                                                    @endif
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
                @endif
            </div>
        </div>
        @if (count($data) > 1)
            {{ $employees->links() }}
        @endif

        <!-- Full Text Modal -->
        <div class="modal fade" id="fullTextModal" tabindex="-1" role="dialog" aria-labelledby="fullTextModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fullTextModalLabel">Full Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="fullTextContent" style="white-space: pre-wrap; word-wrap: break-word;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
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

            $(document).on('click', '.view-full-text', function() {
                var title = $(this).data('title');
                var content = $(this).data('content');
                $('#fullTextModalLabel').text(title);
                $('#fullTextContent').text(content);
                $('#fullTextModal').modal('show');
            });
        </script>
    @endsection
