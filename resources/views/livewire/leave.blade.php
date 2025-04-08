<div>
    <div class="card-body" wire:poll.2000ms>  
        <ul class="timeline">
            @foreach($leaves=$this->fetchleaves() as $leave_request)
            <li>
                <a href="{{route('employee_leave_request')}}" class="font-weight-semibold fs-15 ms-3">{{$leave_request->user->name}}</a>
                <p class="text-muted float-end fs-13">{{$leave_request->status}}</p>
                <p class="mb-0 pb-0 pt-1 font-weight-semibold fs-13 ms-3">{{$leave_request->subject}}</p>
                <span class="font-weight-semibold  ms-3 fs-13">{{'Start Date : '.$leave_request->start_date.' End Date : '.$leave_request->end_date}}</span>
            </li>
            @endforeach
        </ul>
        <div class="row">
            <a href="{{route('employee_leave_request')}}"><p class="mb-0 pb-0 pt-1 font-weight-semibold fs-13 ms-3" align="right">View More</p></a>
        </div>
    </div>
</div>
