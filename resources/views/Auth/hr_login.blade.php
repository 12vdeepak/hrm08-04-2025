
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
    <div class="page-single" id ="form-login">
        <div class="container">
            <div class="row">
                <div class="col mx-auto">
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6 col-xl-4 col-xxl-4">
                            <div class="card my-5">
                                <div class="p-4 pt-6 text-center">
                                    <h1 class="mb-2">Quantum HR Login</h1>
                                </div>
                                <form class="card-body pt-3 " action="{{ route('hr_login_post') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <div class="input-group mb-4">
                                            <div class="input-group">
                                                <a href="" class="input-group-text">
                                                    <i class="fe fe-mail" aria-hidden="true"></i>
                                                </a>
                                                <input class="form-control" placeholder="Email" type="email" name="email" id="email" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Password</label>
                                        <div class="input-group mb-4">
                                            <div class="input-group" id="Password-toggle">
                                                <a href="" class="input-group-text">
                                                    <i class="fe fe-eye-off" aria-hidden="true"></i>
                                                </a>
                                                <input class="form-control" type="password" placeholder="Password" name="password" id="password" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="submit">
                                        <button class="btn btn-primary btn-block">Login</button>
                                    </div>
                                </form>
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
