<div>
    <div class="card-header border-bottom pb-5">
        <h4 class="card-title mx-2">Notifications </h4>
        <div class="card-options">
            <a href="javascript:void(0);" class="btn btn-sm btn-icon btn-light text-primary" data-bs-toggle="sidebar-right"
                data-bs-target=".sidebar-right"><i class="feather feather-x"></i> </a>
        </div>
        @if (auth()->user()->role_id == 2)
            <a href="{{ route('mark-as-alll-read-hr') }}">Mark all as read </a>
        @elseif(auth()->user()->role_id == 3)
            <a href="{{ route('mark-as-all-read-user') }}">Mark all as read </a>
        @endif
    </div>
    <div wire:poll.5000ms>
        @forelse ($unreadNotifications=$this->fetchnotifications() as $notification)
            <div class="list-group-item align-items-center border-0">
                <div>
                    @php
                        // Make sure data is properly decoded as an array
                        $notificationData = is_array($notification->data)
                            ? $notification->data
                            : json_decode($notification->data, true);
                        $notificationType = $notificationData['notification_type'] ?? 'Unknown';
                    @endphp
                    <div class="mt-1">
                        @if (isset($notificationData['notification_type']) && $notificationData['notification_type'] == 'Leave Request')
                            <a href="{{ route('employee_leave_request') }}" class="font-weight-semibold fs-16">New
                                {{ $notificationData['notification_type'] }}</a>
                            <span class="clearfix"></span>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted font-weight-normal">Leave Requested by
                                    {{ $notificationData['user'] ?? 'Employee' }} </span>
                                <span class="text-muted fs-13"><i
                                        class="mdi mdi-clock text-muted me-1"></i>{{ $notification->created_at->shortAbsoluteDiffForHumans() . ' ago' }}</span>
                            </div>
                        @elseif(isset($notificationData['notification_type']) && $notificationData['notification_type'] == 'Holiday')
                            @if (auth()->user()->role_id == 3)
                                <a href="{{ route('all-holidays') }}" class="font-weight-semibold fs-16">New
                                    {{ $notificationData['notification_type'] }} on occasion of
                                    {{ $notificationData['occasion'] ?? 'Holiday' }}</a>
                            @elseif(auth()->user()->role_id == 2)
                                <a href="{{ route('holiday.index') }}" class="font-weight-semibold fs-16">New
                                    {{ $notificationData['notification_type'] }} on occasion of
                                    {{ $notificationData['occasion'] ?? 'Holiday' }}</a>
                            @endif
                            <span class="clearfix"></span>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted font-weight-normal">Holiday from
                                    {{ $notificationData['start_date'] ?? 'N/A' }} to
                                    {{ $notificationData['end_date'] ?? 'N/A' }}</span>
                                <span class="text-muted fs-13"><i
                                        class="mdi mdi-clock text-muted me-1"></i>{{ $notification->created_at->shortAbsoluteDiffForHumans() . ' ago' }}</span>
                            </div>
                        @elseif(isset($notificationData['notification_type']) && $notificationData['notification_type'] == 'announcement')
                            @if (auth()->user()->role_id == 3)
                                <a href="{{ route('view-announcement', ['id' => $notificationData['notification_id'] ?? 0]) }}"
                                    class="font-weight-semibold fs-16">New
                                    {{ $notificationData['notification_type'] }}</a>
                            @elseif(auth()->user()->role_id == 2)
                                <a href="{{ route('announcement.index') }}" class="font-weight-semibold fs-16">New
                                    {{ $notificationData['notification_type'] }}</a>
                            @endif
                            <span class="clearfix"></span>
                            <div class="d-flex justify-content-between">
                                <span
                                    class="text-muted font-weight-normal">{{ $notificationData['title'] ?? 'New Announcement' }}</span>
                                <span class="text-muted fs-13"><i
                                        class="mdi mdi-clock text-muted me-1"></i>{{ $notification->created_at->shortAbsoluteDiffForHumans() . ' ago' }}</span>
                            </div>
                        @elseif(isset($notificationData['notification_type']) && $notificationData['notification_type'] == 'File Upload')
                            @if (auth()->user()->role_id == 3)
                                <a href="{{ route('employee.files') }}" class="font-weight-semibold fs-16">New File
                                    Upload</a>
                            @elseif(auth()->user()->role_id == 2)
                                <a href="{{ route('hr.upload.form') }}" class="font-weight-semibold fs-16">New File
                                    Upload</a>
                            @endif
                            <span class="clearfix"></span>
                            <div class="d-flex justify-content-between">
                                <span
                                    class="text-muted font-weight-normal">{{ $notificationData['message'] ?? 'A new file has been uploaded' }}</span>
                                <span class="text-muted fs-13"><i
                                        class="mdi mdi-clock text-muted me-1"></i>{{ $notification->created_at->shortAbsoluteDiffForHumans() . ' ago' }}</span>
                            </div>
                        @else
                            @if (auth()->user()->role_id == 3)
                                <a href="{{ route('view_company_policy') }}" class="font-weight-semibold fs-16">
                                    {{ $notificationType }}</a>
                            @elseif(auth()->user()->role_id == 2)
                                <a href="{{ route('company-policy.index') }}" class="font-weight-semibold fs-16">
                                    {{ $notificationType }}</a>
                            @endif
                            <span class="clearfix"></span>
                            <div class="d-flex justify-content-between">
                                <span
                                    class="text-muted font-weight-normal">{{ $notificationData['message'] ?? 'You have a new notification' }}</span>
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
