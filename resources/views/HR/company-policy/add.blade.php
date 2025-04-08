@extends('layouts.hr_app')
@section('content')
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <div class="page-title">Add Company Policy</div>
        </div>
    </div>

    <div class="col-xl-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header  border-0">
                <h4 class="card-title">Add Details</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('company-policy.store') }}" method = "POST">
                    @csrf
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea type="text" rows = 5 id="description" name="description" class="form-control @error('description') is-invalid @enderror" placeholder="Description"></textarea>
                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>


@endsection

@section('modals')

    <!-- Announcement modal-->
    <div class="modal fade" id="annoucement">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Announcement</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('announcement.store') }}" method ="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Type..</label>
                            <textarea rows = 5 class="form-control @error('announcement') is-invalid @enderror" placeholder="Announcement description" name = "announcement" required></textarea>
                            @error('announcement')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">Add</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <!-- end holiday modal-->


@endsection()
