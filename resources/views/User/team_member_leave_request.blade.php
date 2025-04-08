@extends('layouts.user_app')

@section('styles')
@endsection
@include('livewire.hr-employee-model')
@section('content')
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <div class="page-title">Team Members Leave Requests</div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-xl-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="hr-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    @if(count($leave_requests)>0)
                                    <table
                                        class="table table-vcenter text-nowrap table-bordered border-bottom dataTable no-footer"
                                        id="hr-table" role="grid" aria-describedby="hr-table_info">
                                        <thead>
                                            <tr>
                                                <th>S No.</th>
                                                <th>Employee Name</th>
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($leave_requests as $leave_request)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{$leave_request['name']}}</td>
                                                <td>{{$leave_request['subject']}}</td>
                                                <td>{{$leave_request['description']}}</td>
                                                <td>{{$leave_request['start_date']}}</td>
                                                <td>{{$leave_request['end_date']}}</td>
                                                <td>{{$leave_request['status']}}</td>
                                                @if($leave_request['status']=="Requested")
                                                <td>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <a href="{{route('approve_team_member_leave_application', ['id'=>$leave_request['id']])}}"><i class="fa fa-check" style="color:green" aria-hidden="true"></i></a>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <a href="{{route('reject_team_member_leave_application', ['id'=>$leave_request['id']])}}"><i class="fa fa-times" style="color:red" aria-hidden="true"></i></a>
                                                        </div>
                                                    </div>
                                                </td>
                                                @else
                                                <td>-</td>
                                                @endif
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @else
                                    <h4>No Leave Request Made Yet</h4>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endsection


            @section('scripts')
            @endsection