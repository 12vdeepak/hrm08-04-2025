@extends('layouts.auth_app')
@section('styles')
    <style>
        html,
        body,
        .page-single {
            height: 100%;
        }

        #form-login {
            display: flex;
            align-items: center;
        }
    </style>
@endsection
@section('content')
    <div class="page-single" id="form-login">
        <div class="container">
            <div class="row">
                <div class="col mx-auto">
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6 col-xl-4 col-xxl-4">
                            <div class="card my-5">
                                <div class="p-4 pt-6 text-center">
                                    <h1 class="mb-2">Token Expired</h1>
                                </div>
                                <div class="p-4 pt-6 text-center">
                                <h4>Password for the user has already been created</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- END PAGE -->
@endsection
@section('scripts')
@endsection