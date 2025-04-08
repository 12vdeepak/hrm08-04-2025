@extends('layouts.hr_app')

@section('styles')
<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
		<!-- CIRCLE-PROGRESS JS -->
		<script src="{{asset('assets/plugins/circle-progress/circle-progress.min.js')}}"></script>
@endsection
@section('content')
    <!-- PAGE HEADER -->
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <div class="page-title">Attendance</div>
        </div>
    </div>
    <!--END PAGE HEADER -->
    @livewire('h-r.report')
@endsection
