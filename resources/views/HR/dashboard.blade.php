@extends('layouts.hr_app')

@section('styles')
    <script src="{{ asset('js/app.js') }}" defer></script>
@endsection

@section('content')
    <div id="app">
        <!-- PAGE HEADER -->
        <div class="page-header d-xl-flex d-block">
            <div class="page-leftheader" style="margin-bottom: 2.5rem">
                <div class="page-title">HR<span class="font-weight-normal text-muted ms-2">Dashboard</span></div>
            </div>
            <div class="page-rightheader ms-md-auto">
                <div class="d-flex align-items-end flex-wrap my-auto end-content breadcrumb-end">
                    <div class="d-flex">
                        <div class="header-datepicker me-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="feather feather-calendar"></i>
                                    </div>
                                </div><input class="form-control fc-datepicker" placeholder="{{ date('d M Y') }}"
                                    type="text">
                            </div>
                        </div>
                        <div class="header-datepicker me-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="feather feather-clock"></i>
                                    </div>
                                </div><!-- input-group-prepend -->
                                <input id="check_in_duration" type="text" class="form-control input-small">
                                <input id="timer" type="text" class="form-control input-small" hidden
                                    value={{ $response['time'] }}>
                                <input id="timer_onn" type="text" class="form-control input-small" hidden
                                    value={{ $response['button_status'] }}>
                            </div>
                            <label for=" " style="margin-top: 2px; font-size: 13px; ">Total Login Time</label>
                        </div><!-- wd-150 -->
                    </div>
                    <div class="d-lg-flex d-block">
                        <div class="btn-list">
                            @if ($response['button_status'] == 1)
                                <button type="button" class="btn btn-primary" onclick="checkin()" id="check_in_button"
                                    name="check_in_button" style="margin-bottom: 2.5rem ">Clock In</button>
                            @else
                                <button type="button" class="btn btn-danger" onclick="checkin()" id="check_in_button"
                                    name="check_in_button" style="margin-bottom: 2.5rem ">Clock Out</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--END PAGE HEADER -->

        <!--ROW-->
        <div class="row">
            <div class="col-xl-9 col-md-12 col-lg-12">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="mt-0 text-start"> <span class="fs-14 font-weight-semibold">Total
                                                Employees</span>
                                            <h3 class="mb-0 mt-1 mb-2">{{ $count['employees'] }}</h3>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="icon1 bg-success my-auto  float-end"> <i
                                                class="feather feather-users"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="mt-0 text-start"> <span
                                                class="fs-14 font-weight-semibold">Department</span>
                                            <h3 class="mb-0 mt-1 mb-2">{{ $count['departments'] }}</h3>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="icon1 bg-primary my-auto  float-end"> <i
                                                class="feather feather-box"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="mt-0 text-start"> <span
                                                class="fs-14 font-weight-semibold">Locations</span>
                                            <h3 class="mb-0 mt-1  mb-2">{{ $count['locations'] }}</h3>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="icon1 bg-secondary brround my-auto  float-end"> <i
                                                class="feather feather-navigation"></i> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header border-0 responsive-header">
                                <h4 class="card-title">Leave Requests</h4>
                            </div>
                            @livewire('leave')
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-12 col-lg-12">
                <div class="card overflow-hidden">
                    <div class="card-header border-0">
                        <h4 class="card-title">New Employees</h4>
                    </div>
                    <div class="pt-2">
                        <div class="list-group">
                            @foreach ($employees as $employee)
                                <div class="list-group-item d-flex pt-3 pb-3 border-0">
                                    <div class="me-3 me-xs-0">
                                        <div class="calendar-icon icons">
                                            <div class="date_time bg-pink-transparent"> <span
                                                    class="date">{{ date('d', strtotime($employee->created_at)) }}</span>
                                                <span
                                                    class="month">{{ date('M', strtotime($employee->created_at)) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ms-1">
                                        <div class="h5 fs-14 mb-1"> {{ $employee->id }} - {{ $employee->name }}
                                            {{ $employee->last_name }}</div>
                                        <small
                                            class="text-muted">{{ $employee->department ? $employee->department->name : '' }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var seconds = 0;

        var interval = null;

        function updateTimer() {
            var time = document.getElementById("timer").value;
            Time(time);
            document.getElementById("timer").value = parseInt(time) + 1;
        }

        function startTimer() {
            interval = setInterval(updateTimer, 1000);
        }

        function stopTimer() {
            clearInterval(interval);
        }

        function Time(seconds) {
            var hours = Math.floor(seconds / 3600);
            var minutes = Math.floor((seconds % 3600) / 60);
            var seconds = Math.floor(seconds % 60);
            minutes = (minutes < 10 ? '0' : '') + minutes;
            seconds = (seconds < 10 ? '0' : '') + seconds;
            var timeString = hours + ':' + minutes + ':' + seconds;
            var time_display = document.getElementById("check_in_duration");
            time_display.value = timeString;
        }

        function checkin() {
            $.ajax({
                url: '{{ route('hr_checkin') }}',
                type: "GET",
                success: function(response) {
                    console.log(response['button_status']);
                    if (response['button_status'] == 2) {
                        var button = document.getElementById("check_in_button");
                        button.classList.remove("btn-primary");
                        button.classList.add("btn-danger");
                        button.textContent = "Clock Out";
                        Time(response['time']);
                        document.getElementById("timer").value = response['time'];
                        startTimer();
                        // startActivityTracker();
                        Livewire.emit('refreshComponent');
                    } else {
                        var button = document.getElementById("check_in_button");
                        button.classList.remove("btn-danger");
                        button.classList.add("btn-primary");
                        button.textContent = "Clock In";
                        Time(response['time']);
                        stopTimer();
                    }
                },
            });
        }

        updateTimer();
        if (document.getElementById("timer_onn").value == 2) {
            startTimer();
        }
    </script>
@endsection
