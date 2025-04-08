@extends('layouts.user_app')

@section('styles')
@endsection
@section('content')
    <div class="page-header d-block">
        <div class="page-leftheader">
            <div class="page-title">Company Policy</div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    {!! $company_policy->description !!}
                </div>
            @endsection


            @section('scripts')
            @endsection
