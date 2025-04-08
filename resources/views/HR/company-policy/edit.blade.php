@extends('layouts.hr_app')
@section('content')
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <div class="page-title">Edit Company Policy</div>
        </div>
    </div>




    <div class="col-xl-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header  border-0">
                <h4 class="card-title">Edit Details</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('company-policy.update',['company_policy' => $company_policy]) }}" method = "POST">
                    @csrf
                    @method('put')
                    <div class="form-group">
                        <textarea id = "post_content" type="text" rows = 5 id="description" name="description" class="form-control @error('description') is-invalid @enderror" placeholder="Description">{{ $company_policy->description }}</textarea>
                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>


@endsection

@section('modals')
@endsection()

@section('scripts')
    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('post_content');
    </script>
@endsection
