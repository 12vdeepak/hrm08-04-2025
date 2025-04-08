@extends('layouts.user_app')

@section('styles')
@endsection

@section('content')
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <div class="page-title">Change Password</div>
        </div>
    </div>

    <div class="row">
        <form action="{{ route('change-password-post') }}" method="POST">
            @csrf
            <div class="col-md-12 ">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 col-lg-2">
                                    <label class="form-label mb-0 mt-2">New Password</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" value="{{old('password')}}"
                                        placeholder="New Password" name="password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 col-lg-2">
                                    <label class="form-label mb-0 mt-2">Confirm Password</label>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <input type="password" class="form-control @error('cpassword') is-invalid @enderror"
                                        placeholder="Confirm Password" name="cpassword">
                                    @error('cpassword')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>
                    
                    <div class = "card-footer">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection