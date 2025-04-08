@extends('layouts.hr_app')

@section('styles')
    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
@endsection

@section('content')
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <div class="page-title">Edit Time Tracker</div>
        </div>
    </div>

    <div class="row">
        <form action="{{route('update_hr_time_tracker_info',['time_tracker_info'=>$time_tracker_info])}}" method="POST">
            @csrf
            <div class="col-md-12 ">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 col-lg-2">
                                    <label class="form-label mb-0 mt-2">Project Name</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <select
                                        class="form-control @error('project_name') is-invalid @enderror custom-select select2"
                                        data-placeholder="Select" tabindex="-1" aria-hidden="true" name="project_name">
                                        <option value="" label="Select"
                                            {{ old('project_name') == '' ? 'selected' : '' }}>Select</option>
                                        @foreach ($project_names as $project_name)
                                            <option value="{{ $project_name->id }}"
                                                @selected($time_tracker_info->project_id == $project_name->id ? 'selected' : '')
                                                >
                                                {{ $project_name->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('project_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-12 col-lg-2">
                                    <a class="btn btn-primary btn-success text-white mt-4 mt-lg-0" data-bs-toggle="modal"
                                        data-bs-target="#project_name"><i class="fa fa-plus me-1"></i>Add</a></button>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('#projectname-form').on('submit', function(e) {
                                        e.preventDefault();
                                        $.ajax({
                                            url: '{{ route('add_project_name') }}',
                                            type: "POST",
                                            data: $('#projectname-form').serialize(),
                                            success: function(response) {
                                                $('#project_name').modal('hide');
                                                console.log(response);
                                                var projectname = response.project_name;
                                                var dropdown = $('select[name="project_name"]');
                                                var newOption = $('<option>').val(projectname.id).text(projectname
                                                    .name);
                                                dropdown.prepend(newOption);
                                            },
                                            error: function(error) {
                                                console.log(error);
                                            }
                                        });
                                    });
                                });
                            </script>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 col-lg-2">
                                    <label class="form-label mb-0 mt-2">Job Name</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <select
                                        class="form-control @error('job_name') is-invalid @enderror custom-select select2 select2-hidden-accessible"
                                        data-placeholder="Select" tabindex="-1" aria-hidden="true" name="job_name">
                                        <option value="" label="Select" {{ old('job_name') == '' ? 'selected' : '' }}>
                                            Select</option>
                                        @foreach ($job_names as $job_name)
                                            <option value="{{ $job_name->id }}"
                                                @selected($time_tracker_info->job_id == $job_name->id ? 'selected':'')
                                                >
                                                {{ $job_name->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('job_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-12 col-lg-2">
                                    <a class="btn btn-primary btn-success text-white mt-4 mt-lg-0" data-bs-toggle="modal"
                                        data-bs-target="#job_name"><i class="fa fa-plus me-1"></i>Add</a></button>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('#jobname-form').on('submit', function(e) {
                                        e.preventDefault();
                                        $.ajax({
                                            url: '{{ route('add_job_name') }}',
                                            type: "POST",
                                            data: $('#jobname-form').serialize(),
                                            success: function(response) {
                                                $('#job_name').modal('hide');
                                                console.log(response);
                                                var jobname = response.job_name;
                                                var dropdown = $('select[name="job_name"]');
                                                var newOption = $('<option>').val(jobname.id).text(jobname.name);
                                                dropdown.prepend(newOption);
                                            },
                                            error: function(error) {
                                                console.log(error);
                                            }
                                        });
                                    });
                                });
                            </script>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 col-lg-2">
                                    <label class="form-label mb-0 mt-2">Date</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <input type="date" class="form-control @error('date') is-invalid @enderror"
                                        placeholder="Date" name="date" value={{ $time_tracker_info->work_date }}>
                                    @error('date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 col-lg-2">
                                    <label class="form-label mb-0 mt-2">Work Description</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <input type="text"
                                        class="form-control @error('work_description') is-invalid @enderror"
                                        placeholder="Work Description" name="work_description"
                                        value="{{ $time_tracker_info->work_title }}">
                                    @error('work_description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 col-lg-2">
                                    <label class="form-label mb-0 mt-2">Time</label>
                                </div>

                                <div class="col-md-12 col-lg-8 mt-3">
                                   
                                      
                                    <div class="col" id="hours"
                                        style="">
                                        <input type="text" name="hours"
                                            class="form-control col-2 @error('hours') is-invalid @enderror"
                                            value="{{ $time_tracker_info->work_time }}">
                                        @error('hours')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    

                                          
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary" type="submit">Update</button>
            </div>
        </form>
    </div>
@endsection

@section('modals')
    <div class="modal fade" id="project_name" tabindex="-1" style="background: rgba(0,0,0,0.4);">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Project Name</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="projectname-form" action="{{ route('add_project_name') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Project Name</label>
                            <input type="text" class="form-control" name="project_name" required>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="job_name" tabindex="-1" style="background: rgba(0,0,0,0.4);">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Job Name</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="jobname-form" action="{{ route('add_job_name') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Job Name</label>
                            <input type="text" class="form-control" name="job_name" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });

        function showHideTimeLogTimer(value) {
            var hoursDiv = document.getElementById('hours');
            var rangeDiv = document.getElementById('range');

            if (value === '0') {
                hoursDiv.style.display = 'block'; // Show the hours div
                rangeDiv.style.display = 'none'; // Hide the range div
            } else if (value === '1') {
                hoursDiv.style.display = 'none'; // Hide the hours div
                rangeDiv.style.display = 'block'; // Show the range div
            }
        }
    </script>
@endsection
