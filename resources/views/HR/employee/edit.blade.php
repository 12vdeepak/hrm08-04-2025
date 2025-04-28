@extends('layouts.hr_app')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <style>
        .password-container {
            position: relative;
        }

        .password-container input[type="password"],
        .password-container input[type="text"] {
            width: 100%;
            box-sizing: border-box;
        }

        .fa-eye {
            position: absolute;
            top: 33%;
            right: 2%;
            cursor: pointer;
            color: lightgray;
        }
    </style>
@endsection

@section('content')
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <div class="page-title">Edit Employee</div>
        </div>
    </div>

    <div class="row">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('employee.update', ['employee' => $employee]) }}" method="POST">
            @csrf
            @method('put')
            <div class="col-md-12 ">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Basic Info</h4>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 col-lg-2">
                                    <label class="form-label mb-0 mt-2">First Name</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <input type="text" class="form-control @error('firstname') is-invalid @enderror"
                                        value="{{ $employee->name }}" placeholder="First Name" name="firstname">
                                    @error('firstname')
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
                                    <label class="form-label mb-0 mt-2">Last Name</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <input type="text" class="form-control @error('lastname') is-invalid @enderror"
                                        value="{{ $employee->lastname }}" placeholder="First Name" name="lastname">
                                    @error('lastname')
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
                                    <label class="form-label mb-0 mt-2">Email ID</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        value="{{ $employee->email }}" placeholder="Email" name="email" >
                                    @error('email')
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
                                    <label class="form-label mb-0 mt-2">Password</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <div class="password-container">
                                        <input type="password" class="form-control" placeholder="Password" id="password"
                                            value="{{ $employee->view_password }}" name="password">
                                        <i class="fa-solid fa-eye" id="eye"></i>
                                    </div>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h4 class="mb-5 mt-6 font-weight-bold">Work</h4>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 col-lg-2">
                                    <label class="form-label mb-0 mt-2">Department</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <select
                                        class="form-control @error('Department') is-invalid @enderror custom-select select2 select2-hidden-accessible"
                                        data-placeholder="Select" tabindex="-1" aria-hidden="true" name="Department">
                                        <option value="" {{ old('Department') == '' ? 'selected' : '' }}
                                            label="Select">Select Department</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}"
                                                {{ $employee->department_id == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('Department')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-12 col-lg-2">
                                    <a class="btn btn-primary btn-success text-white mt-4 mt-lg-0" data-bs-toggle="modal"
                                        data-bs-target="#Department"><i class="fa fa-plus me-1"></i>Add</a></button>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('#department-form').on('submit', function(e) {
                                        console.log("HILHDli");
                                        e.preventDefault();
                                        $.ajax({
                                            url: '{{ route('add_department') }}',
                                            type: "POST",
                                            data: $('#department-form').serialize(),
                                            success: function(response) {
                                                $('#Department').modal('hide');
                                                console.log(response);
                                                var storedDepartment = response.department;
                                                var dropdown = $('select[name="Department"]');
                                                var newOption = $('<option>').val(storedDepartment.id).text(
                                                    storedDepartment.name);
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
                                    <label class="form-label mb-0 mt-2">Location</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <select
                                        class="form-control @error('locations') is-invalid @enderror custom-select select2 select2-hidden-accessible"
                                        data-placeholder="Select" tabindex="-1" aria-hidden="true" name="locations">
                                        <option value="" {{ old('locations') == '' ? 'selected' : '' }}
                                            label="Select">Select Location</option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}"
                                                {{ $employee->location_id == $location->id ? 'selected' : '' }}>
                                                {{ $location->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('locations')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-12 col-lg-2">
                                    <a class="btn btn-primary btn-success text-white mt-4 mt-lg-0" data-bs-toggle="modal"
                                        data-bs-target="#Location"><i class="fa fa-plus me-1"></i>Add</a></button>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('#location-form').on('submit', function(e) {
                                        e.preventDefault();
                                        $.ajax({
                                            url: '{{ route('add_location') }}',
                                            type: "POST",
                                            data: $('#location-form').serialize(),
                                            success: function(response) {
                                                $('#Location').modal('hide');
                                                console.log(response);
                                                var storedLocation = response.location;
                                                var dropdown = $('select[name="locations"]');
                                                var newOption = $('<option>').val(storedLocation.id).text(storedLocation
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
                                    <label class="form-label mb-0 mt-2">Reporting To</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <select
                                        class="form-control @error('reporting_to') is-invalid @enderror custom-select select2 select2-hidden-accessible"
                                        data-placeholder="Select" tabindex="-1" aria-hidden="true" name="reporting_to">
                                        <option value="">Select</option>
                                        @foreach ($reporting_managers->where('id', '!=', $employee->id) as $reporting_manager)
                                            <option value="{{ $reporting_manager->id }}"
                                                {{ $reporting_manager->id == $employee->reporting_to ? 'selected' : '' }}>
                                                {{ $reporting_manager->name . ' ' . $reporting_manager->lastname }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('reporting_to')
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
                                    <label class="form-label mb-0 mt-2">Source of Hire</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <select
                                        class="form-control @error('Source') is-invalid @enderror custom-select select2 select2-hidden-accessible"
                                        data-placeholder="Select" tabindex="-1" aria-hidden="true" name="Source">
                                        <option value="" {{ old('Source') == '' ? 'selected' : '' }}
                                            label="Select">Select Source</option>
                                        @foreach ($sources as $source)
                                            <option value="{{ $source->id }}"
                                                {{ $employee->source_hire == $source->id ? 'selected' : '' }}>
                                                {{ $source->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('Source')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-12 col-lg-2">
                                    <a class="btn btn-primary btn-success text-white mt-4 mt-lg-0" data-bs-toggle="modal"
                                        data-bs-target="#Source"><i class="fa fa-plus me-1"></i>Add</a></button>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('#sourceofhire-form').on('submit', function(e) {
                                        e.preventDefault();
                                        $.ajax({
                                            url: '{{ route('add_source_of_hire') }}',
                                            type: "POST",
                                            data: $('#sourceofhire-form').serialize(),
                                            success: function(response) {
                                                $('#Source').modal('hide');
                                                console.log(response);
                                                var storedSource = response.source_of_hire;
                                                var dropdown = $('select[name="Source"]');
                                                var newOption = $('<option>').val(storedSource.id).text(storedSource
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
                                    <label class="form-label mb-0 mt-2">Title</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <select
                                        class="form-control @error('Title') is-invalid @enderror custom-select select2 select2-hidden-accessible"
                                        data-placeholder="Select" tabindex="-1" aria-hidden="true" name="Title">
                                        <option value="" {{ old('Title') == '' ? 'selected' : '' }} label="Select">
                                            Select Title</option>
                                        @foreach ($titles as $title)
                                            <option value="{{ $title->id }}"
                                                {{ $employee->title_id == $title->id ? 'selected' : '' }}>
                                                {{ $title->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('Title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-12 col-lg-2">
                                    <a class="btn btn-primary btn-success text-white mt-4 mt-lg-0" data-bs-toggle="modal"
                                        data-bs-target="#Title1"><i class="fa fa-plus me-1"></i>Add</a></button>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('#title-form').on('submit', function(e) {
                                        e.preventDefault();
                                        $.ajax({
                                            url: '{{ route('add_title') }}',
                                            type: "POST",
                                            data: $('#title-form').serialize(),
                                            success: function(response) {
                                                $('#Title1').modal('hide');
                                                console.log(response);
                                                var storedTitle = response.title;
                                                var dropdown = $('select[name="Title"]');
                                                var newOption = $('<option>').val(storedTitle.id).text(storedTitle
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
                                    <label class="form-label mb-0 mt-2">Date Of Joining</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <input type="date"
                                        class="form-control @error('date') is-invalid @enderror fc-datepicker hasDatepicker"
                                        value="{{ $employee->date_of_joining }}" placeholder="DD-MM-YYYY"
                                        id="dp1683195362870" name="date">
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
                                    <label class="form-label mb-0 mt-2">Date Of Birth</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <input type="date"
                                        class="form-control @error('date_of_birth') is-invalid @enderror fc-datepicker hasDatepicker"
                                        value="{{ $employee->date_of_birth }}" placeholder="DD-MM-YYYY"
                                        id="dp1683195362870" name="date_of_birth">
                                    @error('date_of_birth')
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
                                    <label class="form-label mb-0 mt-2">Employee Status</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <select
                                        class="form-control @error('employee_status') is-invalid @enderror custom-select select2 select2-hidden-accessible"
                                        data-placeholder="Select" tabindex="-1" aria-hidden="true"
                                        name="employee_status">
                                        <option value="" label="Select">Select</option>
                                        <option value="1"
                                            {{ $employee->employee_status == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0"
                                            {{ $employee->employee_status == '0' ? 'selected' : '' }}>In Active
                                        </option>
                                    </select>
                                    @error('employee_status')
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
                                    <label class="form-label mb-0 mt-2">Employee Type</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <select
                                        class="form-control @error('Type') is-invalid @enderror custom-select select2 select2-hidden-accessible"
                                        data-placeholder="Select" tabindex="-1" aria-hidden="true" name="Type">
                                        <option value="" {{ old('Type') == '' ? 'selected' : '' }} label="Select">
                                            Select Type</option>
                                        @foreach ($types as $type)
                                            <option value="{{ $type->id }}"
                                                {{ $employee->employee_type_id == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('Type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-12 col-lg-2">
                                    <a class="btn btn-primary btn-success text-white mt-4 mt-lg-0" data-bs-toggle="modal"
                                        data-bs-target="#Type"><i class="fa fa-plus me-1"></i>Add</a></button>
                                </div>
                                <script>
                                    $(document).ready(function() {
                                        $('#employee-type-form').on('submit', function(e) {
                                            e.preventDefault();
                                            $.ajax({
                                                url: '{{ route('add_employee_type') }}',
                                                type: "POST",
                                                data: $('#employee-type-form').serialize(),
                                                success: function(response) {
                                                    $('#Type').modal('hide');
                                                    console.log(response);
                                                    var storedType = response.employee_type;
                                                    var dropdown = $('select[name="Type"]');
                                                    var newOption = $('<option>').val(storedType.id).text(storedType.name);
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
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 col-lg-2">
                                    <label class="form-label mb-0 mt-2">Work Phone</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <input type="text" class="form-control @error('work_phone') is-invalid @enderror"
                                        value="{{ $employee->work_phone }}" placeholder="Work Phone" name="work_phone">
                                    @error('work_phone')
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
                                    <label class="form-label mb-0 mt-2">Role</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <select
                                        class="form-control @error('role') is-invalid @enderror custom-select select2 select2-hidden-accessible"
                                        data-placeholder="Select" tabindex="-1" aria-hidden="true" name="role">
                                        <option value="" label="Select">Select</option>
                                        <option value="2" {{ $employee->role_id == '2' ? 'selected' : '' }}>HR
                                        </option>
                                        <option value="4" {{ $employee->role_id == '4' ? 'selected' : '' }}>Reporting
                                            Manager</option>
                                        <option value="3" {{ $employee->role_id == '3' ? 'selected' : '' }}>Employee
                                        </option>
                                    </select>
                                    @error('Type')
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
                                    <label class="form-label mb-0 mt-2">Experience</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <select
                                        class="form-control @error('experience') is-invalid @enderror custom-select select2 select2-hidden-accessible"
                                        data-placeholder="Select" tabindex="-1" aria-hidden="true" name="experience">
                                        <option value="" label="Select">Select</option>
                                        <option value="0" {{ $employee->experience == '0' ? 'selected' : '' }}>No
                                            experience</option>
                                        <option value="0.5" {{ $employee->experience == '0.5' ? 'selected' : '' }}>6
                                            months</option>
                                        <option value="1" {{ $employee->experience == '1' ? 'selected' : '' }}>1 year
                                        </option>
                                        <option value="2" {{ $employee->experience == '2' ? 'selected' : '' }}>2
                                            years
                                        </option>
                                        <option value="3" {{ $employee->experience == '3' ? 'selected' : '' }}>3
                                            years
                                        </option>
                                        <option value="4" {{ $employee->experience == '4' ? 'selected' : '' }}>4
                                            years
                                        </option>
                                        <option value="5" {{ $employee->experience == '5' ? 'selected' : '' }}>5 year
                                            +
                                        </option>
                                    </select>
                                    @error('experience')
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
                                    <label class="form-label mb-0 mt-2">Working Hours</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <select
                                        class="form-control @error('working_hours') is-invalid @enderror custom-select select2 select2-hidden-accessible"
                                        data-placeholder="Select" tabindex="-1" aria-hidden="true"
                                        name="working_hours">
                                        <option value="" label="Select">Select</option>
                                        <option value="0" {{ $employee->working_hours == '0' ? 'selected' : '' }}>
                                            Fixed
                                            Working Hours</option>
                                        <option value="1" {{ $employee->working_hours == '1' ? 'selected' : '' }}>
                                            Flexible Working Hours</option>
                                    </select>
                                    @error('working_hours')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @if( $employee->employee_status == 0)
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 col-lg-2">
                                    <label class="form-label mb-0 mt-2">HR Remark</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <input type="text" class="form-control @error('hr-remark') is-invalid @enderror"
                                        value="{{ $employee->hr_remark }}" placeholder=
                                        "HR Remark" name="hr_remark">
                                    @error('hr-remark')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <h4 class="mb-5 mt-2 font-weight-bold">Personal</h4>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 col-lg-2">
                                    <label class="form-label mb-0 mt-2">Mobile Phone</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <input type="text" class="form-control @error('firstname') is-invalid @enderror"
                                        value="{{ $employee->phone }}" placeholder="Phone" name="phone">
                                    @error('phone')
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
                                    <label class="form-label mb-0 mt-2">Address</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <input type="text" class="form-control @error('firstname') is-invalid @enderror"
                                        value="{{ $employee->address }}" placeholder="Address" name="address">
                                    @error('address')
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
                                    <label class="form-label mb-0 mt-2">Other Email</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <input type="text" class="form-control @error('other_email') is-invalid @enderror"
                                        value="{{ $employee->other_email }}" placeholder="Email" name="other_email">
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
    <!--department MODAL -->
    <div class="modal fade" id="Department" tabindex="-1" style="background: rgba(0,0,0,0.3)">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Department</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('add_department') }}" method="POST" id="department-form">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Department Name</label>
                            <input type="text" class="form-control" name="department" required>
                        </div>
                        @error('department')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="modal-footer">
                            <button class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END Department MODAL -->

    <!--location MODAL -->
    <div class="modal fade" id="Location" tabindex="-1" style="background: rgba(0,0,0,0.3)">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Location</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="location-form" action="{{ route('add_location') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Location Name</label>
                            <input type="text" class="form-control" name="location" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END location MODAL -->

    <!--location MODAL -->
    <div class="modal fade" id="Source" style="background: rgba(0,0,0,0.3)">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Soure of Hire</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="sourceofhire-form" action="{{ route('add_source_of_hire') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Source Name</label>
                            <input type="text" class="form-control" name="source" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END location MODAL -->

    <!--location MODAL -->
    <div class="modal fade" id="Type" style="background: rgba(0,0,0,0.3)">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Employee Type</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="employee-type-form" action="{{ route('add_employee_type') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Employee Type</label>
                            <input type="text" class="form-control" name="employee_type" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END location MODAL -->

    <!--title MODAL -->
    <div class="modal fade" id="Title1" style="background: rgba(0,0,0,0.3)">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Location</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="title-form" action="{{ route('add_title') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Title Name</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END location MODAL -->
@endsection

@section('scripts')
    <script>
        const passwordInput = document.querySelector("#password");
        const eye = document.querySelector("#eye");
        eye.addEventListener("click", function() {
            this.classList.toggle("fa-eye-slash")
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password"
            passwordInput.setAttribute("type", type)
        });

        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endsection
