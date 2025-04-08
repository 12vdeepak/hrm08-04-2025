<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta
        content="DayOne - It is one ohe Major Dashboard Template which includes - HR, Employee and Job Dashboard. This template has multipurpose HTML template and also deals with Task, Project, Client and Support System Dashboard."
        name="description">
    <meta content="Spruko Technologies Private Limited" name="author">
    <meta name="keywords" content="admin dashboard, admin panel template, html admin template, dashboard html template, bootstrap 5 dashboard, template admin bootstrap 5 , simple admin panel template, simple dashboard html template,  bootstrap admin panel, task dashboard, job dashboard, bootstrap admin panel, dashboards html, panel in html, bootstrap 5 dashboard, bootstrap 5 dashboard, bootstrap5 dashboard" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- TITLE -->
    <title>HRM</title>



    @include('layouts.components.styles')
    @livewireStyles
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="app sidebar-mini ltr">

    <!--- GLOBAL-LOADER -->
    <div id="global-loader">
        <img src="{{ asset('assets/images/svgs/loader.svg') }}" alt="loader">
    </div>
    <!--- END GLOBAL-LOADER -->

    <div class="page">
        <div class="page-main">
            @if ($errors->any())
                <script>
                    $(document).ready(function() {
                        $('#{{ session('openModal') }}').modal('show');
                    });
                </script>
            @endif
            <!-- APP-HEADER -->
            <div class="app-header header sticky">
                <div class="container-fluid main-container">
                    <div class="d-flex">
                        <a class="header-brand" href="{{ url('index') }}">
                            <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img desktop-lgo"
                                alt="Dayonelogo">
                            <img src="{{ asset('assets/images/brand/logo-white.png') }}"
                                class="header-brand-img dark-logo" alt="Dayonelogo">
                            <img src="{{ asset('assets/images/brand/favicon.png') }}"
                                class="header-brand-img mobile-logo" alt="Dayonelogo">
                            <img src="{{ asset('assets/images/brand/favicon1.png') }}"
                                class="header-brand-img darkmobile-logo" alt="Dayonelogo">
                        </a>
                        <div class="app-sidebar__toggle" data-bs-toggle="sidebar">
                            <a class="open-toggle" href="javascript:void(0);">
                                <i class="feather feather-menu"></i>
                            </a>
                            <a class="close-toggle" href="javascript:void(0);">
                                <i class="feather feather-x"></i>
                            </a>
                        </div>
                        <div class="d-flex order-lg-2 my-auto ms-auto">
                            <button class="navbar-toggler nav-link icon navresponsive-toggler vertical-icon ms-auto"
                                type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4"
                                aria-controls="navbarSupportedContent-4" aria-expanded="false"
                                aria-label="Toggle navigation">
                                <i class="fe fe-more-vertical header-icons navbar-toggler-icon"></i>
                            </button>
                            <div
                                class="mb-0 navbar navbar-expand-lg navbar-nav-right responsive-navbar navbar-dark p-0">
                                <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                                    <div class="d-flex ms-auto">
                                        <a class="nav-link  icon p-0 nav-link-lg d-lg-none navsearch"
                                            href="javascript:void(0);" data-bs-toggle="search">
                                            <i class="feather feather-search search-icon header-icon"></i>
                                        </a>
                                        <div class="dropdown  d-flex">
                                            <a class="nav-link icon theme-layout nav-link-bg layout-setting">
                                                <span class="dark-layout"><i class="fe fe-moon"></i></span>
                                                <span class="light-layout"><i class="fe fe-sun"></i></span>
                                            </a>
                                        </div>

                                        <div class="dropdown header-fullscreen">
                                            <a class="nav-link icon full-screen-link">
                                                <i
                                                    class="feather feather-maximize fullscreen-button fullscreen header-icons"></i>
                                                <i
                                                    class="feather feather-minimize fullscreen-button exit-fullscreen header-icons"></i>
                                            </a>
                                        </div>
                                        <div class="dropdown header-notify">
                                            <a class="nav-link icon" data-bs-toggle="sidebar-right"
                                                data-bs-target=".sidebar-right">
                                                <i class="feather feather-bell header-icon"></i>
                                                @livewire('dot')
                                            </a>
                                        </div>
                                        <div class="dropdown profile-dropdown">
                                            <a href="javascript:void(0);" class="nav-link pe-1 ps-0 leading-none"
                                                data-bs-toggle="dropdown">
                                                <span>
                                                    <img src="{{ asset('assets/images/users/16.jpg') }}" alt="img"
                                                        class="avatar avatar-md bradius">
                                                </span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow animated">
                                                {{-- <div class="p-3 text-center border-bottom">
														<a href="{{url('profile1')}}" class="text-center user pb-0 font-weight-bold">John Thomson</a>
														<p class="text-center user-semi-title">App Developer</p>
													</div>
													 <a class="dropdown-item d-flex" href="{{url('profile1')}}">
														<i class="feather feather-user me-3 fs-16 my-auto"></i>
														<div class="mt-1">Profile</div>
													</a>
													<a class="dropdown-item d-flex" href="{{url('editprofile')}}">
														<i class="feather feather-settings me-3 fs-16 my-auto"></i>
														<div class="mt-1">Settings</div>
													</a>
													<a class="dropdown-item d-flex" href="{{url('chat')}}">
														<i class="feather feather-mail me-3 fs-16 my-auto"></i>
														<div class="mt-1">Messages</div>
													</a> --}}
                                                <a class="dropdown-item d-flex" href="javascript:void(0);"
                                                    data-bs-toggle="modal" data-bs-target="#changepasswordnmodal">
                                                    <i class="feather feather-edit-2 me-3 fs-16 my-auto"></i>
                                                    <div class="mt-1">Change Password</div>
                                                </a>
                                                <a class="dropdown-item d-flex" href="{{ route('logout') }}">
                                                    <i class="feather feather-power me-3 fs-16 my-auto"></i>
                                                    <div class="mt-1">Sign Out</div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- APP-HEADER CLOSED -->

            @include('layouts.components.hr_app-sidebar')

            <div class="app-content main-content">
                <div class="side-app main-container">
                    @yield('content')
                </div>
            </div><!-- end app-content-->
        </div>

        @include('layouts.components.footer')

        @include('layouts.components.right-sidebar')

        @include('layouts.components.modal')

    </div>
    @yield('modals')

    @include('layouts.components.scripts')
    
    @livewire('activity-tracker')
    
    @livewireScripts


    <!---toaster-->
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
    <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
    function deleteLeaveRequest(id) {
        if (confirm("Are you sure you want to delete this leave request?")) {
            // Send a POST request to delete-leave-request route
            axios.post(`/delete-leave-request/${id}`)
                .then(response => {
                    if (response.data.success) {
                        // Reload the page or update the UI as needed
                        location.reload();
                    } else {
                        alert("Failed to delete the leave request.");
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert("An error occurred while deleting the leave request.");
                });
        }
    }
</script>

    <script>
        if(localStorage.getItem("darkMode")){
            $("body").addClass("dark-mode");
            $("body").removeClass("light-mode");
            $("body").removeClass("transparent-mode");
        }
        @if (Session::has('error'))
            toastr.error("{{ Session::get('error') }}");
        @endif
        @if (Session::has('success'))
            toastr.success("{{ Session::get('success') }}");
        @endif
    </script>
</body>

</html>
