@extends('layouts.user_app') {{-- or your employee layout --}}

@section('content')
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <div class="page-title">Available HR Documents</div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-body table-responsive">
                    @if ($files->count())
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>File Name</th>
                                    <th>Uploaded At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($files as $file)
                                    <tr>
                                        <td>{{ $file->original_name }}</td>
                                        <td>{{ $file->created_at->format('d M Y, h:i A') }}</td>
                                        <td>
                                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                                class="btn btn-sm btn-info">
                                                View / Download
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No files available.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
