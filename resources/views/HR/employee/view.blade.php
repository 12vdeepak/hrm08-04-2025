@extends('layouts.hr_app')

@section('styles')
    <style>
        td {
            font-size: 14px;
        }
    </style>
@endsection
@include('livewire.hr-employee-model')
@section('content')
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <div class="page-title">Employee Details</div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-xl-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header  border-0">
                    <h4 class="card-title text-success">Employee Info</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <h4>Basic Info</h4>
                            <div class="dataTables_length" id="hr-table_length">
                                <label class="form-label">First Name</b></label>
                                <input type="text" class="form-control" value="{{ $employee_info->name }}" disabled>
                            </div>

                            <div class="dataTables_length" id="hr-table_length">
                                <label class="form-label">Last Name</b></label>
                                <input type="text"class="form-control" value="{{ $employee_info->lastname }}" disabled>
                            </div>

                            <div class="dataTables_length" id="hr-table_length">
                                <label class="form-label">Email Id</b></label>
                                <input type="text"class="form-control" value="{{ $employee_info->email }}" disabled>
                            </div>

                            <div class="dataTables_length" id="hr-table_length">
                                <label class="form-label">Password</b></label>
                                <input type="text"class="form-control" value="{{ $employee_info->view_password }}"
                                    disabled>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-4">
                            <h4>Work Info</h4>
                            <div class="dataTables_length" id="hr-table_length">
                                <label>Department</label>
                                <input type="text"class="form-control"
                                    value="{{ $employee_info->department ? $employee_info->department->name : '' }}"
                                    disabled>
                            </div>
                            <div class="dataTables_length" id="hr-table_length">
                                <label>Location</label>
                                <input type="text"class="form-control"
                                    value="{{ $employee_info->location ? $employee_info->location->name : '' }}" disabled>
                            </div>

                            <div class="dataTables_length" id="hr-table_length">
                                <label>Title</label>
                                <input type="text" class="form-control"
                                    value="{{ $employee_info->title ? $employee_info->title->name : '' }}" disabled>
                            </div>

                            <div class="dataTables_length" id="hr-table_length">
                                <label>Date of Joining</label>
                                <input type="date"class="form-control" value="{{ $employee_info->date_of_joining }}"
                                    disabled>
                            </div>

                            <div class="dataTables_length" id="hr-table_length">
                                <label>Employee Status</label>
                                @if ($employee_info->employee_status == 1)
                                    <input type="text"class="form-control" value="Active" disabled>
                                @else
                                    <input type="text"class="form-control" value="Inactive" disabled>
                                @endif
                            </div>

                            <div class="dataTables_length" id="hr-table_length">
                                <label>Employee Type</label>
                                <input type="text"class="form-control"
                                    value="{{ $employee_info->employee_type ? $employee_info->employee_type->name : '' }}"
                                    disabled>
                            </div>
                            @if ($employee_info->employee_status == 0)
                                <div class="dataTables_length" id="hr-table_length">
                                    <label>HR Remark</label>
                                    <input type="text"class="form-control"
                                        value="{{ $employee_info->hr_remark}}"
                                        disabled>
                                </div>
                               
                            @endif
                        </div>

                        <div class="col-sm-12 col-md-4">
                            <h4>Personal Info</h4>
                            <div class="dataTables_length" id="hr-table_length">
                                <label>Phone</label>
                                <input type="text"class="form-control" value="{{ $employee_info->phone }}" disabled>
                            </div>

                            <div class="dataTables_length" id="hr-table_length">
                                <label>Address</label>
                                <input type="text"class="form-control" value="{{ $employee_info->address }}" disabled>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-xl-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header border-0 justify-content-between">
                    <h4 class="card-title">Employee Attendance</h4>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_length" id="hr-table_length">
                                <a href="{{ route('employee-attendance-pdf', ['user_id' => $employee_info->id, 'start_date' => $start_date, 'end_date' => $end_date]) }}"
                                    class="btn text-white" style="background: black;">Download</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="hr-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="row">
                                <div class="col-sm-12 col-md-3">
                                    <div class="dataTables_length" id="hr-table_length">
                                        <label><b>Start Date</b></label>
                                        <input type="date" class="form-control" name="start_date" id="start_date"
                                            value="{{ $start_date }}">
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-3">
                                    <div class="dataTables_length" id="hr-table_length">
                                        <label><b>End Date</b></label>
                                        <input type="date" name="end_date" class="form-control" id="end_date"
                                            value="{{ $end_date }}">
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
                            <br>
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-vcenter table-bordered border-bottom" role="grid"
                                        aria-describedby="hr-table_info" id="emp-attendance">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>First In</th>
                                                <th>Location(In)</th>
                                                <th>Last Out</th>
                                                <th>Location(Out)</th>
                                                <th>Time</th>
                                                <th>Comment</th>
                                                <th>Status</th>
                                                <th>Remark</th>
                                                <th>Edit</th>
                                                {{-- <th>Time Tracker</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($employee_attendances as $employee_attendance)
                                                <tr>
                                                    <td class="text-nowrap">
                                                        {{ date('d-m-Y', strtotime($employee_attendance['date'])) }}</td>
                                                    <td>{{ $employee_attendance['check_in'] }}</td>
                                                    <td>{{ $employee_attendance['check_in_location'] }}</td>
                                                    <td>{{ $employee_attendance['check_out'] }}</td>
                                                    <td>{{ $employee_attendance['check_out_location'] }}</td>
                                                    <td>
                                                        @php
                                                            // Get the time value from the configuration
                                                            $configTime = Carbon\Carbon::createFromFormat('H', config('constants.variable.permitted_work_hours'));
                                                            // Create a Carbon instance from the time in the format "00:04"
                                                            $customTime = Carbon\Carbon::createFromFormat('H:i', $employee_attendance['time']);
                                                        @endphp
                                                        <p
                                                            class="@if ($customTime < $configTime) text-danger @endif m-0">
                                                            {{ $employee_attendance['time'] }}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-light btn-icon btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#view_comment{{ $loop->iteration }}">
                                                            <i class="feather feather-eye" data-bs-toggle="tooltip"
                                                                data-original-title="View"></i>
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge @if ($employee_attendance['status'] == 'Present') badge-success-light @elseif($employee_attendance['status'] == 'Absent') badge-danger-light @else badge-pink-light @endif">
                                                            {{ $employee_attendance['status'] }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $employee_attendance['remark'] }}</td>
                                                    <td>
                                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                                            data-bs-target="#edit_log_time{{ $loop->iteration }}"
                                                            wire:click="edit_log_time({{ $employee_attendance['date'] }})">
                                                            Edit
                                                        </button>
                                                    </td>
                                                <tr>
                                                    <!--edit modal -->
                                                    <div class="modal fade" id="edit_log_time{{ $loop->iteration }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="updateStudentModalLabel">
                                                                        Edit LogTime</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" wire:click="closeModal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <form action="{{ route('update_log_time') }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <input type="text" name="id"
                                                                        class="form-control"
                                                                        value={{ $employee_info->id }} hidden>
                                                                    <input type="text" name="date"
                                                                        class="form-control"
                                                                        value={{ $employee_attendance['date'] }} hidden>
                                                                    <input type="text" name="start_date"
                                                                        class="form-control" value={{ $start_date }}
                                                                        hidden>
                                                                    <input type="text" name="end_date"
                                                                        class="form-control" value={{ $end_date }}
                                                                        hidden>
                                                                    <div class="modal-body">
                                                                        <div class="mb-3">
                                                                            <label>Check In Time</label>
                                                                            <input type="time" name="start_time"
                                                                                class="form-control"
                                                                                value={{ $employee_attendance['check_in'] }}>
                                                                            @error('start_time')
                                                                                <span
                                                                                    class="text-danger">{{ $message }}</span>
                                                                            @enderror
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label>Check Out Time</label>
                                                                            <input type="time" name="end_time"
                                                                                class="form-control"
                                                                                value={{ $employee_attendance['check_out'] }}>
                                                                            @error('end_time')
                                                                                <span
                                                                                    class="text-danger">{{ $message }}</span>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            wire:click="closeModal"
                                                                            data-bs-dismiss="modal">Close</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Update</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--edit modal -->

                                                    <!--view modal -->
                                                    <div class="modal fade" id="view_comment{{ $loop->iteration }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="updateStudentModalLabel">
                                                                        Comments</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" wire:click="closeModal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <form action="{{ route('update_comment') }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <input type="text" name="id"
                                                                            class="form-control"
                                                                            value={{ $employee_info->id }} hidden>
                                                                        <input type="text" name="date"
                                                                            class="form-control"
                                                                            value={{ $employee_attendance['date'] }}
                                                                            hidden>
                                                                        <input type="text" name="start_date"
                                                                            class="form-control" value={{ $start_date }}
                                                                            hidden>
                                                                        <input type="text" name="end_date"
                                                                            class="form-control" value={{ $end_date }}
                                                                            hidden>
                                                                        <div class="mb-3">
                                                                            <label>HR Comment</label>
                                                                            <textarea name="comment" class="form-control" required>{{ $employee_attendance['hr_comment'] }}</textarea>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label>System Comment</label>
                                                                            <textarea name="system_comment" class="form-control" readonly>{{ $employee_attendance['comment'] }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Update</button>
                                                                        <button type="button" class="btn btn-secondary"
                                                                            wire:click="closeModal"
                                                                            data-bs-dismiss="modal">Close</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--modal -->
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        function dateChange() {
            var start_date = document.getElementById("start_date").value;
            var end_date = document.getElementById("end_date").value;
            var url =
                "{{ route('employee_detail', ['id' => ':id', 'start_date' => ':start_date', 'end_date' => ':end_date']) }}";
            url = url.replace(':id', {{ $employee_info->id }});
            url = url.replace(':start_date', start_date);
            url = url.replace(':end_date', end_date);
            console.log(url);
            window.location.href = url;
        }
    </script>
@endsection
