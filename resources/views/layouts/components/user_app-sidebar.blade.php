
				<!-- APP-SIDEBAR -->
				<div class="sticky">
					<aside class="app-sidebar " >
						<div class="app-sidebar2">
							<div class="main-menu">
								<div class="app-sidebar__user">
									<div class="dropdown user-pro-body text-center">
										<div class="user-info">
											<h5 class="text-white mb-2">{{ auth()->user()->name }} {{auth()->user()->lastname}}</h5>
											<span class="text-muted app-sidebar__user-name text-sm">{{ auth()->user()->title->name }}</span>
											<span class="text-muted app-sidebar__user-name" style="font-size: 14px; font-weight:400;">{{ auth()->user()->department->name }}</span>
										</div>
									</div>
								</div>
								<div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"/></svg></div>
								<ul class="side-menu">

                                    <li class="slide">
										<a class="side-menu__item"  href="{{route('user_dashboard')}}">
											<i class="feather feather-home sidemenu_icon"></i>
											<span class="side-menu__label">Dashboard</span>
										</a>
									</li>

									<li class="slide">
										<a class="side-menu__item"  href="{{route('attendance', ['start_date'=>0, 'end_date'=>0])}}">
											<i class="feather feather-calendar sidemenu_icon"></i>
											<span class="side-menu__label">Attendence</span>
										</a>
									</li>

									<li class="slide">
										<a class="side-menu__item"  href="{{route('view_time_tracker_info', ['start_date'=>0, 'end_date'=>0])}}">
											<i class="feather feather-file-text sidemenu_icon"></i>
											<span class="side-menu__label">Time Tracker</span>
										</a>
									</li>

									<li class="slide">
										<a class="side-menu__item"  href="{{route('view_leave_request')}}">
											<i class="fa fa-user-times sidemenu_icon"></i>
											<span class="side-menu__label">Leave</span>
										</a>
									</li>

                                    <li class="slide">
										<a class="side-menu__item"  href="{{route('all-holidays')}}">
											<i class="feather feather-calendar sidemenu_icon"></i>
											<span class="side-menu__label">Holidays</span>
										</a>
									</li>
									
									<li class="slide">
										<a class="side-menu__item"  href="{{route('change-password')}}">
											<i class="fa fa-lock sidemenu_icon"></i>
											<span class="side-menu__label">Change Password</span>
										</a>
									</li>

									<li class="slide">
										<a class="side-menu__item"  href="{{ route('logout') }}">
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
