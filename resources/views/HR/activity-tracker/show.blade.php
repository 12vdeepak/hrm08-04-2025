@extends('layouts.hr_app')
@section('content')
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <div class="page-title">Employees Activity Tracker</div>
        </div>
    </div>
    <div class="d-flex flex-row mb-5">
        <div class="me-4">
            <input type="date" class="form-control" name="date" id="date" value="{{ $date }}">
            <input type="hidden" name="id" id="id" value="{{ $id }}">
        </div>
        <button class="btn btn-primary" onclick="dateChange()">Apply</button>
    </div>
    <div class="row mt-5">
        <div class="col-xl-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header  border-0">
                    <h4 class="card-title">Employee Activity Data</h4>
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
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                                <th>Total Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($mappedActivities as $activity)
                                                <tr class="odd">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $activity['activity']->start_time }}</td>
                                                    <td>{{ $activity['activity']->end_time }}</td>
                                                    <td>{{ $activity['total_time'] }}</td>
                                                </tr>

                                            @empty

                                                <tr colspan="4">
                                                    <td>No record found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
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
                var date = document.getElementById("date").value;
                var id = document.getElementById("id").value;
                var url = "{{ route('activity_tracker.show.with', ['id' => ':id', 'date' => ':date']) }}";
                url = url.replace(':date', date);
                url = url.replace(':id', id);
                console.log(url);
                window.location.href = url;
            }
        </script>
    @endsection
