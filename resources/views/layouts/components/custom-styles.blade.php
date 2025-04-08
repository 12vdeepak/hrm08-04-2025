
		<!--FAVICON -->
		<link rel="icon" href="{{asset('assets/images/brand/favicon.ico')}}" type="image/x-icon"/>

		<!-- BOOTSTRAP CSS -->
		<link href="{{asset('assets/plugins/bootstrap/css/bootstrap.css')}}" rel="stylesheet" />

		<!-- STYLE CSS -->
		<link href="{{asset('assets/css/style.css')}}" rel="stylesheet" />
		<link href="{{asset('assets/css/plugins.css')}}" rel="stylesheet" />

		<!-- ANIMATE CSS -->
		<link href="{{asset('assets/css/animated.css')}}" rel="stylesheet" />

		<!---ICONS CSS -->
		<link href="{{asset('assets/plugins/icons/icons.css')}}" rel="stylesheet" />

        <!--toastr css-->
        <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">


        <style>
            .mybadge-container {
            position: fixed;
            top: 50px;
            left: 0;
            right: 0;
            z-index: 9999;
            text-align: center;
            }
            .mybadge {
                display: inline-block;
                padding: 1em;
                font-size: 1em;
                font-weight: 200;
                line-height: 1;
                color: #fff;
                text-align: center;
                white-space: nowrap;
                vertical-align: baseline;
                border-radius: 0.45rem;
                position: absolute;
                margin-left: auto;
                margin-right: auto;
                left: 0;
                right: 0;
                z-index: 2;
                width: fit-content;
            }

            .badge-green{
                border:1px solid rgb(131, 238, 124);
                background-color:rgb(191, 242, 187);
                color:rgb(40, 80, 38);
            }
            .badge-red{
                border:1px solid rgb(237, 136, 105);
                background-color:rgba(237, 167, 167, 0.7);
                color:rgb(123, 73, 59);
            }
        </style>
        @yield('styles')
