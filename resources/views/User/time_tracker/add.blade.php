@extends('layouts.user_app')

@section('styles')
    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <style>
        #toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1055;
        }
    </style>
@endsection

@section('content')
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <div class="page-title">Add Time Tracker</div>
        </div>
    </div>

    <div id="toast-container"></div>

    <div class="row">
        <form action="{{ route('create_time_tracker_info') }}" method="POST">
            @csrf
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        {{-- Project Name --}}
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 col-lg-2">
                                    <label class="form-label mb-0 mt-2">Project Name</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <select class="form-control @error('project_name') is-invalid @enderror select2"
                                        name="project_name" data-placeholder="Select">
                                        <option value="" {{ old('project_name') == '' ? 'selected' : '' }}>Select
                                        </option>
                                        @foreach ($project_names as $project_name)
                                            <option value="{{ $project_name->id }}"
                                                {{ old('project_name') == $project_name->id ? 'selected' : '' }}>
                                                {{ $project_name->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('project_name')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="col-md-12 col-lg-2">
                                    <a class="btn btn-success text-white mt-4 mt-lg-0" data-bs-toggle="modal"
                                        data-bs-target="#project_name">
                                        <i class="fa fa-plus me-1"></i>Add
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Project Type (only for certain departments) --}}
                        @if ($showProjectStartDate)
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12 col-lg-2">
                                        <label class="form-label mb-0 mt-2">Project Type <span
                                                class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-12 col-lg-8">
                                        <select class="form-control @error('project_type') is-invalid @enderror"
                                            name="project_type" id="project_type">
                                            <option value="" {{ old('project_type') == '' ? 'selected' : '' }}>Select
                                                Project Type</option>
                                            <option value="development"
                                                {{ old('project_type') == 'development' ? 'selected' : '' }}>Development
                                            </option>
                                            <option value="marketing"
                                                {{ old('project_type') == 'marketing' ? 'selected' : '' }}>Marketing
                                            </option>
                                            <option value="support"
                                                {{ old('project_type') == 'support' ? 'selected' : '' }}>Support</option>
                                            <option value="meeting"
                                                {{ old('project_type') == 'meeting' ? 'selected' : '' }}>Meeting</option>
                                        </select>
                                        @error('project_type')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Job Name --}}
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 col-lg-2">
                                    <label class="form-label mb-0 mt-2">Job Name</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <select class="form-control @error('job_name') is-invalid @enderror select2"
                                        name="job_name" data-placeholder="Select">
                                        <option value="" {{ old('job_name') == '' ? 'selected' : '' }}>Select
                                        </option>
                                        @foreach ($job_names as $job_name)
                                            <option value="{{ $job_name->id }}"
                                                {{ old('job_name') == $job_name->id ? 'selected' : '' }}>
                                                {{ $job_name->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('job_name')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="col-md-12 col-lg-2">
                                    <a class="btn btn-success text-white mt-4 mt-lg-0" data-bs-toggle="modal"
                                        data-bs-target="#job_name">
                                        <i class="fa fa-plus me-1"></i>Add
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Date --}}
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 col-lg-2">
                                    <label class="form-label mb-0 mt-2">Date</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <input type="date" class="form-control @error('date') is-invalid @enderror"
                                        name="date" value="{{ old('date') ?? date('Y-m-d') }}">
                                    @error('date')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Work Description --}}
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 col-lg-2">
                                    <label class="form-label mb-0 mt-2">Work Description</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <input type="text"
                                        class="form-control @error('work_description') is-invalid @enderror"
                                        name="work_description" placeholder="Work Description"
                                        value="{{ old('work_description') }}">
                                    @error('work_description')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Time --}}
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 col-lg-2">
                                    <label class="form-label mb-0 mt-2">Time</label>
                                </div>
                                <div class="col-md-12 col-lg-8 mt-3">
                                    <div id="hours" style="display: {{ old('timelogTime') == 0 ? '' : 'none' }};">
                                        <input type="text" name="hours"
                                            class="form-control @error('hours') is-invalid @enderror"
                                            value="{{ old('hours') ?? '00:00' }}">
                                        @error('hours')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- BA Email + Project Start Date (only for certain departments and conditions) --}}
                        @if ($showProjectStartDate)
                            <div id="ba-section"
                                style="display: {{ isset($time_tracker_info) && $time_tracker_info->project_start_date ? 'none' : 'block' }};">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-2">
                                            <label class="form-label mb-0 mt-2">BA Email <span
                                                    class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-12 col-lg-8">
                                            <input type="email"
                                                class="form-control @error('ba_email') is-invalid @enderror"
                                                name="ba_email" value="{{ old('ba_email') }}"
                                                placeholder="Enter Business Analyst email address">
                                            <small class="text-muted">Email notification will be sent to this
                                                address.</small>
                                            @error('ba_email')
                                                <span class="invalid-feedback"
                                                    role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-2">
                                            <label class="form-label mb-0 mt-2">Project Start Date</label>
                                        </div>
                                        <div class="col-md-12 col-lg-8">
                                            <input type="date"
                                                class="form-control @error('project_start_date') is-invalid @enderror"
                                                name="project_start_date"
                                                value="{{ old('project_start_date', $time_tracker_info->project_start_date ?? '') }}"
                                                readonly>
                                            <small class="text-muted">This field will be filled by BA after email
                                                notification.</small>
                                            @error('project_start_date')
                                                <span class="invalid-feedback"
                                                    role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
    </div>
    <div class="card-footer">
        <button class="btn btn-primary" type="submit">Save</button>
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
                hoursDiv.style.display = 'block';
                rangeDiv.style.display = 'none';
            } else if (value === '1') {
                hoursDiv.style.display = 'none';
                rangeDiv.style.display = 'block';
            }
        }

        function showSuccessToast(message) {
            var toastId = 'success-toast-' + Date.now();
            var toastHtml = `
                <div id="${toastId}" class="toast align-items-center bg-success text-white border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000" style="min-width: 200px;">
                    <div class="d-flex">
                        <div class="toast-body">
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;
            var $toast = $(toastHtml);
            $('#toast-container').append($toast);
            var toast = new bootstrap.Toast($toast[0]);
            toast.show();
            $toast.on('hidden.bs.toast', function() {
                $toast.remove();
            });
        }

        // Function to check if BA section should be shown or hidden
        function checkBaSectionVisibility() {
            var projectId = $('select[name="project_name"]').val();
            var projectType = $('#project_type').val();

            // Hide BA section if:
            // 1. Project type is marketing, support, or meeting
            // 2. Project already has start date and project type is development
            if (projectType === 'marketing' || projectType === 'support' || projectType === 'meeting') {
                $('#ba-section').hide();
            } else if (projectType === 'development' && projectId) {
                // Check if project already has start date
                $.ajax({
                    url: '/projects/' + projectId + '/start-date',
                    type: 'GET',
                    success: function(response) {
                        if (response.exists) {
                            $('#ba-section').hide();
                            $('input[name="project_start_date"]').val(response.start_date);
                        } else {
                            $('#ba-section').show();
                            $('input[name="project_start_date"]').val('');
                        }
                    },
                    error: function(err) {
                        console.error(err);
                    }
                });
            } else if (projectType === 'development') {
                $('#ba-section').show();
            }
        }

        $(document).ready(function() {
            $('#projectname-form').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route('add_project_name') }}',
                    type: "POST",
                    data: $('#projectname-form').serialize(),
                    success: function(response) {
                        $('#project_name').modal('hide');
                        var projectname = response.project_name;
                        var dropdown = $('select[name="project_name"]');
                        var newOption = $('<option>').val(projectname.id).text(projectname
                            .name);
                        dropdown.prepend(newOption);
                        showSuccessToast(response.message);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });

            $('#jobname-form').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route('add_job_name') }}',
                    type: "POST",
                    data: $('#jobname-form').serialize(),
                    success: function(response) {
                        $('#job_name').modal('hide');
                        var jobname = response.job_name;
                        var dropdown = $('select[name="job_name"]');
                        var newOption = $('<option>').val(jobname.id).text(jobname.name);
                        dropdown.prepend(newOption);
                        showSuccessToast(response.message);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });

            // Handle project name change
            $('.select2[name="project_name"]').on('change', function() {
                checkBaSectionVisibility();
            });

            // Handle project type change
            $('#project_type').on('change', function() {
                checkBaSectionVisibility();
            });

            // Initial check on page load
            checkBaSectionVisibility();
        });
    </script>
@endsection
