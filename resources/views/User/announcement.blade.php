@extends('layouts.user_app')
@section('content')
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <div class="page-title">Announcement</div>
        </div>
    </div>

    <div class="col-xl-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title text-success">{{ $announcement->title }}</h4>
            </div>
            <div class="card-body">
           
                {!! $announcement->announcement !!}
            </div>
        </div>
    </div>

@endsection
