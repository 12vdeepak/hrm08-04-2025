@extends('layouts.hr_app')

@section('styles')
@endsection
@include('livewire.hr-employee-model')
@section('content')
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <div class="page-title">Employee Leave Requests</div>
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
                                                    <th>Employee Number</th>

                                                    <th>Employee Name</th>
                                                    <th>Leave Type</th>
                                                    <th>Status</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Total Days</th>
                                                    <th>View</th>
                                                    <th>Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($leave_requests as $leave_request)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $leave_request->secondary_number ?? 'N/A' }}</td>

                                                        <td>{{ $leave_request['name'] }}</td>
                                                        <td>{{ $leave_request['type'] }}</td>
                                                        <td>
                                                            <span
                                                                class="badge @if ($leave_request['status'] == 'Accepted By HR') badge-success-light @else badge-danger-light @endif">
                                                                {{ $leave_request['status'] }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $leave_request['start_date'] }}</td>
                                                        <td>{{ $leave_request['end_date'] }}</td>
                                                        <td>{{ $leave_request['total_days'] }}</td>
                                                        <td>
                                                            <button class="btn btn-primary" data-bs-toggle="modal"
                                                                data-bs-target="#view{{ $loop->iteration }}">View</button>
                                                        </td>
                                                        <td>
                                                            <form method="post"
                                                                action="{{ route('delete-leave-request', ['id' => $leave_request['id']]) }}">
                                                                @csrf
                                                                @method('delete')
                                                                <button type="submit"
                                                                    class="btn btn-danger">Delete</button>
                                                            </form>

                                                        </td>
                                                    </tr>
                                                    <div class="modal fade" id="view{{ $loop->iteration }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="updateStudentModalLabel">
                                                                        Leave Application</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" wire:click="closeModal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <form
                                                                    action="{{ route('reponse_employee_leave_application') }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <input type="text" name="id"
                                                                            class="form-control"
                                                                            value={{ $leave_request['id'] }} hidden>
                                                                        <div class="mb-3">
                                                                            <label>Employee Name</label>
                                                                            <input type="text" class="form-control"
                                                                                readonly
                                                                                value={{ $leave_request['name'] }}></input>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label>Type Of Leave</label>
                                                                            <input type="text" class="form-control"
                                                                                readonly
                                                                                value={{ $leave_request['type'] }}></input>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label>Start Date</label>
                                                                            <input type="text" class="form-control"
                                                                                readonly
                                                                                value={{ $leave_request['start_date'] }}></input>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label>End Date</label>
                                                                            <input type="text" class="form-control"
                                                                                readonly
                                                                                value={{ $leave_request['end_date'] }}></input>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label>Total Days</label>
                                                                            <input type="text" class="form-control"
                                                                                readonly
                                                                                value={{ $leave_request['total_days'] }}></input>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label>Status</label>
                                                                            <input type="text" class="form-control"
                                                                                readonly
                                                                                value={{ $leave_request['status'] }}></input>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label>Title</label>
                                                                            <input type="text" class="form-control"
                                                                                readonly
                                                                                value="{{ $leave_request['subject'] }}"></input>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label>Description</label>
                                                                            <textarea class="form-control" readonly required>{{ $leave_request['description'] }}</textarea>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label>Reporting Manager Email</label>
                                                                            <input type="email"
                                                                                name="reporting_manager_email"
                                                                                class="form-control" required
                                                                                value={{ $leave_request['reporting_manager_email'] }}></input>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label>Reporting Manager Comment</label>
                                                                            <textarea class="form-control" readonly>{{ $leave_request['reporting_manager_comment'] }}</textarea>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label>HR Comment</label>
                                                                            <textarea name="hr_comment" class="form-control">{{ $leave_request['hr_comment'] }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" name="submit_button"
                                                                            class="btn btn-primary" value=0>Forward To
                                                                            Reporting Manager</button>
                                                                        <button type="submit" name="submit_button"
                                                                            class="btn btn-success" value=1>Accept</button>
                                                                        <button type="submit" name="submit_button"
                                                                            class="btn btn-danger" value=2>Decline</button>
                                                                        <button type="button" class="btn btn-danger"
                                                                            wire:click="closeModal"
                                                                            data-bs-dismiss="modal">Close</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="text-center">
                                            <ul class="pagination">
                                                {{ $raw_leave_requests->links('vendor.pagination.bootstrap-5') }}
                                            </ul>
                                        </div>
                                    @else
                                        <h4>No Leave Request Made Yet</h4>
                                    @endif
                                </div>


                                {{-- </div>
                            <div class="row">
                                {{ $leave_requests->links() }}
                            </div> --}}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    @endsection


    @section('scripts')
    @endsection
