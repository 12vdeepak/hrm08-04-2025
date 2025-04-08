<div>
    {{-- Because she competes with no one, no one can compete with her. --}}
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <div class="page-title">Employees Activity Tracker</div>
        </div>
    </div>
    <div class="row">
        <div class="row">
            <div class="col-3">
                <div class="dataTables_length" id="hr-table_length">
                    <input type="date" class="form-control" name="date" id="date" wire:model="date"
                        value="{{ $date }}">
                </div>
            </div>
            <div class="col-3">
                <div class="input-group mb-2">
                    <div class="input-group-text"><i class="feather feather-search"></i></div>
                    <input type="text" wire:model="search" placeholder="Search by Employee Name" id="search"
                        class="form-control" autocomplete="off">
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-xl-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header  border-0">
                    <h4 class="card-title">Employee Activity</h4>
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
                                                <th>S.No.</th>
                                                <th>Emp. Name</th>
                                                <th>Emp. Email</th>
                                                <th>Department</th>
                                                <th>Phone Number</th>
                                                <th>Active Total(Hrs)</th>
                                                <th>View</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($paginatedEmployees as $employee)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $employee->name }} {{ $employee->lastname }}</td>
                                                    <td>{{ $employee->email }}</td>
                                                    <td>{{ $employee->department ? $employee->department->name : '' }}
                                                    </td>
                                                    <td>{{ $employee->phone }}</td>
                                                    <td>{{ $this->getTotalActivityTime($employee->id) }}</td>
                                                    <td>
                                                        <a href="{{ route('activity_tracker.show.with', ['id' => $employee->id, 'date' => $date]) }}"
                                                            class="btn btn-warning btn-sm">
                                                            <i class="feather feather-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                {{ $paginatedEmployees->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
