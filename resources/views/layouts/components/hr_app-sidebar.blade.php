<!-- APP-SIDEBAR -->
<div class="sticky">
    <aside class="app-sidebar ">
        <div class="app-sidebar2">
            <div class="main-menu">
                <div class="app-sidebar__user">
                    <div class="dropdown user-pro-body text-center">
                        <div class="user-info">
                            <h5 class=" mb-2">{{ auth()->user()->name }} {{ auth()->user()->lastname }}</h5>
                            <a href="{{ route('hr_dashboard') }}"><span
                                    class="text-muted app-sidebar__user-name text-sm">Human Resource</span></a>
                        </div>
                    </div>
                </div>
                <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                        width="24" height="24" viewBox="0 0 24 24">
                        <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                    </svg></div>
                <ul class="side-menu">

                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('hr_dashboard') }}">
                            <i class="feather feather-home sidemenu_icon"></i>
                            <span class="side-menu__label">Dashboard</span>
                        </a>
                    </li>
                    <li class="slide">
                        <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                            <i class="feather feather-user sidemenu_icon"></i>
                            <span class="side-menu__label">Employees</span><i class="angle fa fa-angle-right"></i></a>
                        <ul class="slide-menu" style="list-style: none">
                            <li class="slide"><a class="sub-side-menu__item" href="{{ route('employee.index') }}"><span
                                        class="side-menu__label">Active Employees</span></a></li>
                            <li class="slide"><a class="sub-side-menu__item"
                                    href="{{ route('employee.inactive') }}"><span class="side-menu__label">In Active
                                        Employees</span></a></li>
                        </ul>
                    </li>

                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('holiday.index') }}">
                            <i class="feather feather-calendar sidemenu_icon"></i>
                            <span class="side-menu__label">Holidays</span>
                        </a>
                    </li>

                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('employee_attendance') }}">
                            <i class="feather feather-clock sidemenu_icon"></i>
                            <span class="side-menu__label">Attendence</span>
                        </a>
                    </li>

                    <li class="slide">
                        <a class="side-menu__item"
                            href="{{ route('view_employee_time_tracker', ['id' => 0, 'start_date' => 0, 'end_date' => 0]) }}">
                            <i class="feather feather-file-text sidemenu_icon"></i>
                            <span class="side-menu__label">Time Tracker</span>
                        </a>
                    </li>

                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('hr_view_leave_request') }}">
                            <i class="fa fa-user-times sidemenu_icon"></i>
                            <span class="side-menu__label">Leaves</span>
                        </a>
                    </li>

                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('announcement.index') }}">
                            <i class="feather feather-speaker sidemenu_icon"></i>
                            <span class="side-menu__label">Announcement</span>
                        </a>
                    </li>

                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('company-policy.index') }}">
                            <i class="feather feather-book-open sidemenu_icon"></i>
                            <span class="side-menu__label">Company Policy</span>
                        </a>
                    </li>

                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('employee_leave_request') }}">
                            <i class="fa fa-user-times sidemenu_icon"></i>
                            <span class="side-menu__label">Employee Leave Requests</span>
                        </a>
                    </li>
                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('activity_tracker.index.with') }}">
                            <i class="fa fa-user-times sidemenu_icon"></i>
                            <span class="side-menu__label">Activity Tracker</span>
                        </a>
                    </li>


                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('hr.upload.form') }}">
                            <i class="fa fa-user-times sidemenu_icon"></i>
                            <span class="side-menu__label">Sheet Process</span>
                        </a>
                    </li>

                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('logout') }}">
                            <i class="fa fa-sign-out sidemenu_icon"></i>
                            <span class="side-menu__label">LogOut</span>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </aside>
</div>
<!-- APP-SIDEBAR CLOSED -->
