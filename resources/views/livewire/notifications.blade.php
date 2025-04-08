<div>


<div class="card-header border-bottom pb-5">
    <h4 class="card-title mx-2">Notifications </h4>
    <div class="card-options">

        <a href="javascript:void(0);" class="btn btn-sm btn-icon btn-light  text-primary" data-bs-toggle="sidebar-right"
            data-bs-target=".sidebar-right"><i class="feather feather-x"></i> </a>
    </div>
        @if(auth()->user()->role_id==2)
            <a  href="{{route('mark-as-alll-read-hr')}}">Mark all as read </a>
        @elseif(auth()->user()->role_id==3)
            <a href="{{route('mark-as-all-read-user')}}">Mark all as read </a>    
        @endif
</div>
<div wire:poll.5000ms>
        @forelse ($unreadNotifications=$this->fetchnotifications() as $notification)
            <div class="list-group-item  align-items-center border-0">
                <div>
                    @php
                        $data = json_encode($notification->data);
                    @endphp
                    <div class="mt-1">
                        @if ($notification->data['notification_type'] == 'Leave Request')
                            <a href="{{ route('employee_leave_request') }}" class="font-weight-semibold fs-16">New
                                {{ $notification->data['notification_type'] }}</a>
                            <span class="clearfix"></span>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted font-weight-normal">Leave Requested by
                                    {{ $notification->data['user'] }} </span>
                                <span class="text-muted fs-13"><i
                                        class="mdi mdi-clock text-muted me-1"></i>{{ $notification->created_at->shortAbsoluteDiffForHumans() . ' ago' }}</span>
                            </div>
                        @elseif($notification->data['notification_type'] == 'Holiday')
                            @if (auth()->user()->role_id == 3)
                                <a href="{{ route('all-holidays') }}" class="font-weight-semibold fs-16">New
                                    {{ $notification->data['notification_type'] }} on occasion of
                                    {{ $notification->data['occasion'] }}</a>
                            @elseif(auth()->user()->role_id == 2)
                                <a href="{{ route('holiday.index') }}" class="font-weight-semibold fs-16">New
                                    {{ $notification->data['notification_type'] }} on occasion of
                                    {{ $notification->data['occasion'] }}</a>
                            @endif
                            <span class="clearfix"></span>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted font-weight-normal">Holiday from
                                    {{ $notification->data['start_date'] }} to
                                    {{ $notification->data['end_date'] }}</span>
                                </span>
                                <span class="text-muted fs-13"><i
                                        class="mdi mdi-clock text-muted me-1"></i>{{ $notification->created_at->shortAbsoluteDiffForHumans() . ' ago' }}</span>
                            </div>
                        @elseif($notification->data['notification_type'] == 'announcement')
                            @if (auth()->user()->role_id == 3)
                                <a href="{{ route('view-announcement', ['id' => $notification->data['notification_id']]) }}"
                                    class="font-weight-semibold fs-16">New
                                    {{ $notification->data['notification_type'] }}</a>
                            @elseif(auth()->user()->role_id == 2)
                                <a href="{{ route('announcement.index') }}" class="font-weight-semibold fs-16">New
                                    {{ $notification->data['notification_type'] }}</a>
                            @endif
                            <span class="clearfix"></span>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted font-weight-normal">{{ $notification->data['title'] }}</span>
                                </span>
                                <span class="text-muted fs-13"><i
                                        class="mdi mdi-clock text-muted me-1"></i>{{ $notification->created_at->shortAbsoluteDiffForHumans() . ' ago' }}</span>
                            </div>
                        @else
                            @if (auth()->user()->role_id == 3)
                                <a href="{{ route('view_company_policy') }}" class="font-weight-semibold fs-16">
                                    {{ $notification->data['notification_type'] }}</a>
                            @elseif(auth()->user()->role_id == 2)
                                <a href="{{ route('company-policy.index') }}" class="font-weight-semibold fs-16">
                                    {{ $notification->data['notification_type'] }}</a>
                            @endif
                            <span class="clearfix"></span>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted font-weight-normal">{{ $notification->data['message'] }}</span>
                                </span>
                                <span class="text-muted fs-13"><i
                                        class="mdi mdi-clock text-muted me-1"></i>{{ $notification->created_at->shortAbsoluteDiffForHumans() . ' ago' }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="d-flex justify-content-center mt-2">
                <span class="font-weight-semibold fs-16 text-center">No new Notifications</span>
            </div>
        @endforelse
    </div>
</div>