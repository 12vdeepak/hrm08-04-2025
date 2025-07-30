@extends('layouts.user_app')

@section('styles')
@endsection
@include('livewire.hr-employee-model')
@section('content')
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <div class="page-title">Leave Requests</div>
        </div>
        <div class="page-rightheader ms-md-auto">
            <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                <div class="btn-list">
                    <a data-bs-toggle="modal" data-bs-target="#leave" class="btn btn-primary me-3">Add Leave</a>
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
                                <div class="col-sm-12">
                                    @if (count($leave_requests) > 0)
                                        <table
                                            class="table table-vcenter text-nowrap table-bordered border-bottom dataTable no-footer"
                                            id="hr-table" role="grid" aria-describedby="hr-table_info">
                                            <thead>
                                                <tr>
                                                    <th>S No.</th>
                                                    <th>Title</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>View</th>
                                                    <th>Edit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($leave_requests as $leave_request)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $leave_request->subject }}</td>
                                                        <td>{{ $leave_request->start_date }}</td>
                                                        <td>{{ $leave_request->end_date }}</td>
                                                        <td><button class="btn btn-primary" data-bs-toggle="modal"
                                                                data-bs-target="#view{{ $loop->iteration }}">View</button>
                                                        </td>
                                                        <td><button class="btn btn-warning" data-bs-toggle="modal"
                                                                data-bs-target="#edit{{ $loop->iteration }}">Edit</button>
                                                        </td>



                                                    </tr>

                                                    <!----edit modal---->
                                                    <div class="modal fade" id="edit{{ $loop->iteration }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="updateStudentModalLabel">
                                                                        Leave Request</h5>
                                                                    <button class="btn-close" data-bs-dismiss="modal"
                                                                        aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <form
                                                                    action="{{ route('update_leave_request', ['leave' => $leave_request->id]) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <div class="modal-body">

                                                                        <div class="form-group">

                                                                            <label class="form-label">Subject</label>
                                                                            <input type="text" class="form-control"
                                                                                name="subject"
                                                                                value="{{ $leave_request->subject }}"
                                                                                required>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label class="form-label">Description</label>
                                                                            <input type="text" class="form-control"
                                                                                name="description"
                                                                                value="{{ $leave_request->description }}"
                                                                                required>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label class="form-label">Start Date</label>
                                                                            <input type="date" class="form-control"
                                                                                name="start_date"
                                                                                value="{{ $leave_request->start_date }}"
                                                                                required>
                                                                        </div>


                                                                        <div class="form-group">
                                                                            <label class="form-label">End Date</label>
                                                                            <input type="date" class="form-control"
                                                                                name="end_date"
                                                                                value="{{ $leave_request->end_date }}"
                                                                                required>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label class="form-label">Reporting Manager
                                                                                Email</label>
                                                                            <input type="email" class="form-control"
                                                                                name="reporting_manager_email"
                                                                                value="{{ $leave_request->reporting_manager_email }}"
                                                                                required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit"
                                                                            class="btn btn-danger">Edit</button>

                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--edit modal ends----->

                                                    <div class="modal fade" id="view{{ $loop->iteration }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="updateStudentModalLabel">
                                                                        Leave Request</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" wire:click="closeModal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Type</label>
                                                                        <input type="text" class="form-control"
                                                                            name="subject" readonly
                                                                            value="{{ $leave_request->type }}">
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label class="form-label">Subject</label>
                                                                        <input type="text" class="form-control"
                                                                            name="subject" readonly
                                                                            value="{{ $leave_request->subject }}">
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label class="form-label">Description</label>
                                                                        <input type="text" class="form-control"
                                                                            name="description" readonly
                                                                            value="{{ $leave_request->description }}">
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label class="form-label">Start Date</label>
                                                                        <input type="date" class="form-control"
                                                                            name="start_date" readonly
                                                                            value="{{ $leave_request->start_date }}">
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label class="form-label">End Date</label>
                                                                        <input type="date" class="form-control"
                                                                            name="end_date" readonly
                                                                            value="{{ $leave_request->end_date }}">
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label class="form-label">Reporting Manager
                                                                            Email</label>
                                                                        <input type="email" class="form-control"
                                                                            name="reporting_manager_email" readonly
                                                                            value="{{ $leave_request->reporting_manager_email }}">
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label class="form-label">Status</label>
                                                                        <input type="text" class="form-control"
                                                                            name="reporting_manager_email" readonly
                                                                            value="{{ $leave_request->status }}">
                                                                    </div>
                                                                    @if ($leave_request->status != 'Requested')
                                                                        <div class="form-group">
                                                                            <label class="form-label">Reporting Manager
                                                                                Comment</label>
                                                                            <input type="email" class="form-control"
                                                                                name="reporting_manager_email" readonly
                                                                                value="{{ $leave_request->reporting_manager_comment }}">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="form-label">HR Comment</label>
                                                                            <input type="email" class="form-control"
                                                                                name="reporting_manager_email" readonly
                                                                                value="{{ $leave_request->hr_comment }}">
                                                                        </div>
                                                                    @endif

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        wire:click="closeModal"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <h4>No Leave Request Made Yet</h4>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <!--Leave MODAL -->
    <div class="modal fade" id="leave">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Leave Details</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('add_leave_request') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Leave Type</label>
                            <select class="form-control" name="type">
                                <option value="Sick Leave">Sick Leave</option>
                                <option value="Causal Leave">Casual Leave</option>
                                <option value="First Half">First Half</option>
                                <option value="Second Half">Second Leave</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Subject</label>
                            <input type="text" class="form-control" name="subject" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control" name="description" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" name="end_date" required>
                        </div>
                        <!--@if (strlen($reporting_manager_email) > 0)
    -->
                        <!--<div class="form-group">-->
                        <!--    <label class="form-label">Reporting Manager Email</label>-->
                        <!--    <input type="email" class="form-control" name="reporting_manager_email" value={{ $reporting_manager_email }} readonly>-->
                        <!--</div>-->
                    <!--@else-->
                        <!--<div class="form-group">-->
                        <!--    <label class="form-label">Reporting Manager Email</label>-->
                        <!--    <input type="email" class="form-control" name="reporting_manager_email" value="-" readonly>-->
                        <!--</div>-->
                        <!--
    @endif-->
                        <div class="form-group">
                            <label class="form-label">Reporting Manager Email</label>
                            <input type="email" class="form-control" name="reporting_manager_email" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END Leave MODAL -->
@endsection

@section('scripts')
@endsection
