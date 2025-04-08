@extends('layouts.user_app')

@section('styles')

@section('content')

    <!-- PAGE HEADER -->
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader" style="margin-bottom: 2.5rem">
            <div class="page-title">Employee<span class="font-weight-normal text-muted ms-2">Dashboard</span></div>
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
    <div class="row">
        <div class="col-xl-4 col-lg-8 col-md-8">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">Attendance</h3>
                </div>
                <div class="pt-4" style="height: 200px; overflow-y: scroll;">
                    <p id="display_time" style="font-size: 45px; text-align: center;"></p>
                    <p style="font-size: 20px; text-align: center;">{{ date('d M Y') }}</p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-8 col-md-8">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">Leave Requests</h3>
                </div>

                <div class="pt-4" style="height: 200px; overflow-y: scroll;">
                    <div class="table-responsive">
                        <table class="table transaction-table mb-0 text-nowrap">
                            <tbody>
                                @if (count($leave_requests) != 0)
                                    @foreach ($leave_requests as $leave_request)
                                        <tr class="border-bottom">
                                            <td class="d-flex ps-6">
                                                <a href="#"><span
                                                        class="bg-warning warning-border brround d-block me-5 mt-1 h-5 w-5"></span></a>
                                                <div class="my-auto">
                                                    <span
                                                        class="mb-1 font-weight-semibold fs-17">{{ $leave_request->subject }}</span>
                                                    <div class="clearfix"></div>
                                                    <small class="text-muted">{{ $leave_request->status }}</small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <br><br>
                                    <p style="font-size: 25px; text-align: center;">No Leave Requests!</p>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-8 col-md-8">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">Today's Time Tracker</h3>
                </div>

                <div class="pt-4" style="height: 200px; overflow-y: scroll;">
                    <div class="table-responsive">
                        <table class="table transaction-table mb-0 text-nowrap">
                            <tbody>
                                @if (count($time_trackers) != 0)
                                    @foreach ($time_trackers as $time_tracker)
                                        <tr class="border-bottom">
                                            <td class="d-flex ps-6">
                                                <a href="#"><span
                                                        class="bg-orange orange-border brround d-block me-5 mt-1 h-5 w-5"></span></a>
                                                <div class="my-auto">
                                                    <span
                                                        class="mb-1 font-weight-semibold fs-17">{{ $time_tracker->project->name }}</span>
                                                    <div class="clearfix"></div>
                                                    <small class="text-muted">{{ $time_tracker->work_time }}</small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <br><br>
                                    <p style="font-size: 18px; text-align: center;">Time Tracker not updated yet</p>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6 col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">Announcement</h3>
                </div>
                <div class="pt-4" style="height: 200px; overflow-y: scroll;">
                    <div class="table-responsive">
                        <table class="table transaction-table mb-0 text-nowrap">
                            <tbody>
                                @if (count($announcements) != 0)
                                    @foreach ($announcements as $announcement)
                                        <tr class="border-bottom">
                                            <td class="d-flex ps-6">
                                                <span
                                                    class="bg-primary primary-border brround d-block me-5 mt-1 h-5 w-5"></span>
                                                <div class="my-auto">
                                                    <a href="{{ route('view-announcement', $announcement->id) }}">
                                                        <span
                                                            class="mb-1 font-weight-semibold fs-17">{{ $announcement->title }}
                                                        </span>
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="text-end pe-6">
                                                <a class="text-muted d-block fs-16"
                                                    href="javascript:void(0);">{{ $announcement->created_at->format('Y-m-d') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <br><br>
                                    <p style="font-size: 25px; text-align: center;">No Announcement!</p>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">Upcoming Holidays</h3>
                </div>

                <div class="pt-4" style="height: 200px; overflow-y: scroll;">
                    <div class="table-responsive">
                        <table class="table transaction-table mb-0 text-nowrap">
                            <tbody>
                                @if (count($holidays) != 0)
                                    @foreach ($holidays as $holiday)
                                        <tr class="border-bottom">
                                            <td class="d-flex ps-6">
                                                <a href="#"><span
                                                        class="bg-success success-border brround d-block me-5 mt-1 h-5 w-5"></span></a>
                                                <div class="my-auto">
                                                    <span
                                                        class="mb-1 font-weight-semibold fs-17">{{ $holiday->occasion }}</span>
                                                    <div class="clearfix"></div>
                                                    <small
                                                        class="text-muted fs-14">{{ 'Start Date : ' . $holiday->start_date . ' End Date : ' . $holiday->end_date }}</small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <br><br>
                                    <p style="font-size: 25px; text-align: center;">No Holidays!</p>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header border-0">
            <h3 class="card-title">Company Policy</h3>
        </div>
        <div class="card-body border-0">
            {!! $company_policy->description !!}
        </div>
    </div>
@endsection

@section('modals')
@endsection

@section('scripts')
    <script>
        var seconds = 0;

        var interval = null;
 $(document).ready(function(){
  $('#check_in_button').dblclick(function(e){
    e.preventDefault();
  });
});
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
            var time_display_2 = document.getElementById("display_time");
            time_display.value = timeString;
            time_display_2.textContent = timeString + " Hrs";
        }
 
        function checkin() {
            $.ajax({
                url: '{{ route('user_checkin') }}',
                type: "GET",
                success: function(response) {
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
