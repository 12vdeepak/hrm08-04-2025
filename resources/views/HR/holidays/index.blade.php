@extends('layouts.hr_app')

@section('styles')
    {{-- <link rel="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" /> --}}
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
@endsection

@section('content')
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <div class="page-title">Holidays</div>
        </div>
        <div class="page-rightheader ms-md-auto">
            <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                <div class="btn-list">
                    <a href="" class="btn btn-primary me-3" data-bs-toggle = "modal" data-bs-target = "#holidays">Add Holiday</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-body">
                <div id="calendar1"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header  border-0">
                <h4 class="card-title">Holidays Lists</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table  table-vcenter text-nowrap table-bordered border-bottom" id="hr-holiday">
                        <thead>
                            <tr>
                                <th class="border-bottom-0 w-5">SN</th>
                                <th class="border-bottom-0">Start Date</th>
                                <th class="border-bottom-0">End Date</th>
                                <th class="border-bottom-0">Holidays</th>
                                <th class="border-bottom-0">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($holidays as $holiday )
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $holiday->start_date }}</td>
                                    <td>{{ $holiday->end_date }}</td>
                                    <td>{{ $holiday->occasion }}</td>
                                    <td>
                                        <form action="{{ route('holiday.destroy',['holiday' => $holiday]) }}" method = "POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="btn btn-danger btn-icon btn-sm" data-bs-toggle="tooltip" data-original-title="Delete"><i class="feather feather-trash-2"></i></button>
                                        </form>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('modals')

    <!-- holiday modal-->
    <div class="modal fade" id="holidays">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Holidays</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('holiday.store') }}" method ="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Start Date</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="feather feather-calendar"></i>
                                    </div>
                                </div>
                                <input type = "date" class="form-control @error('start_date') is-invalid @enderror" data-bs-toggle="modaldatepicker" placeholder="MM/DD/YYYY" name = "start_date" required>
                                @error('start_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">End Date</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="feather feather-calendar"></i>
                                    </div>
                                </div>
                                <input type = "date" class="form-control @error('end_date') is-invalid @enderror" data-bs-toggle="modaldatepicker" placeholder="MM/DD/YYYY" name = "end_date" required>
                                @error('end_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Enter Occasion</label>
                            <input class="form-control @error('occasion') is-invalid @enderror" placeholder="Occasion Title" name = "occasion" required>
                            @error('occasion')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Notify Before</label>
                            <input type="number" class="form-control @error('notify') is-invalid @enderror" placeholder="(days before)" name = "notify" required>
                            @error('notify')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">Add</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <!-- end holiday modal-->


@endsection()

@section('scripts')

    {{-- <!-- INTERNAL  DATEPICKER JS -->
    <script src="{{asset('assets/plugins/modal-datepicker/datepicker.js')}}"></script>

    <!-- J query -->
    <script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('assets/plugins/jquery-ui/jquery-ui.min.js')}}"></script>

    <!-- INTERNAL FULLCALENDAR JS -->
    <script src="{{asset('assets/plugins/fullcalendar/fullcalendar.min.js')}}"></script> --}}



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $(document).ready(function(){
            var events = @json($events);
            $('#calendar1').fullCalendar({
                header :{
                    left: 'prev,next',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay',
                },
                events : events,
                eventRender: function(event, element) {
                    var color = 'rgb('
                    + Math.floor(Math.random()*150 + 50) + ','
                    + Math.floor(Math.random()*150 + 50) + ','
                    + Math.floor(Math.random()*150 + 50) + ')';
                    // set the background color of the event
                    element.css('background-color', color);
                    // set the text color of the event to white
                    element.find('.fc-title').css('color', 'white');
                    // display the title of the event
                    element.find('.fc-title').text(event.title);
                },
                dayRender: function(date, cell) {
                    if (date.isoWeekday() == 6 || date.isoWeekday() == 7) {
                        cell.css('color', '#777');
                        cell.html('<div class="fc-title mt-5 text-center rounded" style="font-size: 13px;background-color: #b3ffcc;" >Weekend</div>');

                    }
                },
            });

            $('.fc-prev-button, .fc-icon, .fc-next-button').css('padding-bottom','30px');
            $('.fc-prev-button, .fc-icon, .fc-next-button').css('color','black');
            $('.fc-month-button, .fc-agendaWeek-button, .fc-agendaDay-button').css('color','black');

        });
    </script>

    {{-- <!-- INTERNAL INDEX JS -->
    <script src="{{asset('assets/js/hr/hr-holiday.js')}}"></script> --}}

@endsection
