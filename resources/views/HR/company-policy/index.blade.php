@extends('layouts.hr_app')
@section('content')
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <div class="page-title">Company Policy</div>
        </div>
        <div class="page-rightheader ms-md-auto">
            <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                <div class="btn-list">
                    <a href="{{ route('company-policy.edit',['company_policy' => $policy]) }}" class="btn btn-primary me-3">Edit Company Policy</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header  border-0">
                <h4 class="card-title">Company Policy</h4>
            </div>
            <div class="card-body">
                <div>{!! $policy->description !!}</div>
            </div>
        </div>
    </div>


@endsection

@section('modals')
@endsection()
