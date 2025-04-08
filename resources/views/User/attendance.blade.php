@extends('layouts.user_app')

@section('styles')
@endsection
@include('livewire.hr-employee-model')
@section('content')

    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <div class="page-title">Attendance</div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-xl-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="hr-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="row">
                                    <div class="col-sm-12 col-md-5">
                                        <div class="dataTables_length" id="hr-table_length">
                                            <label><b>Start Date</b></label>
                                                <input type="date" class = "form-control" name="start_date" id="start_date" value={{$start_date}}>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-5">
                                        <div class="dataTables_length" id="hr-table_length">
                                            <label><b>End Date</b></label>
                                            <input type="date" class = "form-control" name="end_date" id="end_date" value={{$end_date}}>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-2">
                                        <div class="dataTables_length" id="hr-table_length">
                                            <div class = "form-control border-0">
                                                <button class="btn btn-primary mt-5" onclick="dateChange()">Apply</button>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-12">
                                    <table
                                        class="table table-vcenter text-nowrap table-bordered border-bottom dataTable no-footer"
                                        id="hr-table" role="grid" aria-describedby="hr-table_info">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>First Check In</th>
                                                <th>Last Check Out</th>
                                                <th>Time</th>
                                                <th>HR Comment</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($employee_attendances as $employee_attendance)
                                            <tr>
                                            <td>{{ $employee_attendance['date'] }}</td>
                                            <td>{{ $employee_attendance['check_in'] }}</td>
                                            <td>{{ $employee_attendance['check_out'] }}</td>
                                            <td>{{ $employee_attendance['time'] }}</td>
                                            <td>{{ $employee_attendance['hr_comment'] }}</td>
                                            {{-- <td><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#view_comment{{ $loop->iteration }}" >View</button></td> --}}
                                            <td>
                                                
                                                @if($employee_attendance['status'] == "Present")
                                             <span class="badge  badge-primary-light ">Present</span>
                                             
                                             @elseif($employee_attendance['status'] == "Weekend")
                                              <span class="badge  badge-secondary-light ">{{$employee_attendance['status']}}</span>
                                              @elseif($employee_attendance['status'] == "Absent")
                                               <span class="badge  badge-danger-light ">{{$employee_attendance['status']}}
                                             </span>  
                                               @else
                                               <span class="badge  badge-warning-light ">{{$employee_attendance['status']}}
                                             </span>  
                                               </span>
                                                @endif
                                                
                                            </td>
                                            {{-- <tr>
                                                <div class="modal fade" id="view_comment{{ $loop->iteration }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="updateStudentModalLabel">Comments</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="closeModal"
                                                                    aria-label="Close"></button>
                                                            </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label>HR Comment</label>
                                                                        <textarea name="comment" class="form-control" readonly>{{ $employee_attendance['hr_comment']}}</textarea>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label>System Comment</label>
                                                                        <textarea name="system_comment" class="form-control" readonly>{{ $employee_attendance['comment']}}</textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" wire:click="closeModal"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </tr> --}}
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endsection


            @section('scripts')
                <script>
                    function dateChange() {
                        var start_date = document.getElementById("start_date").value;
                        var end_date = document.getElementById("end_date").value;
                        var url = "{{ route('attendance', ['start_date'=>':start_date', 'end_date'=>':end_date']) }}";
                        url = url.replace(':start_date', start_date);
                        url = url.replace(':end_date', end_date);
                        console.log(url);
                        window.location.href = url;
                    }
                </script>
            @endsection