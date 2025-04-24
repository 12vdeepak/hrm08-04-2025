<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <div class="page-title">Active Employees</div>
        </div>
        <div class="page-rightheader ms-md-auto">
            <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                <div class="btn-list">
                    <a href="{{ route('employee.create') }}" class="btn btn-primary me-3">Add Employee</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            <div class="input-group mb-2">
                <div class="input-group-text"><i class="feather feather-search"></i></div>
                <input type="text" wire:model="search" placeholder="Search by Employee Name" id="user"
                    class="form-control" autocomplete="off">
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-xl-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header  border-0">
                    <h4 class="card-title">Employees List</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="hr-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table
                                        class="table table-vcenter text-nowrap table-bordered border-bottom dataTable no-footer"
                                        id="hr-table" role="grid" aria-describedby="hr-table_info">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Emp Number</th>
                                                <th>Emp Status</th>
                                                <th>Emp Name</th>
                                                <th>Emp Email</th>
                                                <th>Department</th>
                                                <th>Phone Number</th>
                                                <th>View</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($employees as $employee)
                                                <tr class="odd">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $employee->secondary_number ?? 'N/A' }}</td>

                                                    @if ($employee->employee_status == 1)
                                                        <td>Active</td>
                                                    @else
                                                        <td>Inactive</td>
                                                    @endif
                                                    <td>{{ $employee->name }} {{ $employee->lastname }}</td>
                                                    <td>{{ $employee->email }}</td>
                                                    <td>{{ $employee->department ? $employee->department->name : '' }}
                                                    </td>
                                                    <td>{{ $employee->phone }}</td>
                                                    <td>
                                                        <a href="{{ route('employee_detail', ['id' => $employee->id, 'start_date' => 0, 'end_date' => 0]) }}"
                                                            class="btn btn-info btn-sm"><i
                                                                class="feather feather-eye"></i>
                                                            </class=></a>
                                                        <a href="{{ route('employee.edit', ['employee' => $employee]) }}"
                                                            class="btn btn-warning btn-sm"><i
                                                                class="feather feather-edit"></i></a>
                                                        <a href="javascript:void(0)"
                                                            class="btn btn-danger btn-sm delete-btn"
                                                            data-bs-toggle="modal" data-bs-target="#deleteuser"
                                                            data-delete-link="{{ route('employee.destroy', ['employee' => $employee]) }}"><i
                                                                class="feather feather-trash"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                {{ $employees->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
