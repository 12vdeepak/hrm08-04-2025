@extends('layouts.hr_app')

@section('content')
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <div class="page-title">Upload File (PDF, Excel, Word)</div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">


            <div class="card">
                <div class="card-body">
                    <form action="{{ route('hr.upload.file') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="file">Select File</label>
                            <input type="file" name="file" id="file"
                                class="form-control @error('file') is-invalid @enderror" accept=".pdf,.doc,.docx,.xls,.xlsx"
                                required>

                            @error('file')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary mt-2">Upload</button>
                    </form>
                </div>
            </div>

            {{-- Display uploaded files --}}
            @if ($files->count())
                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="card-title">Uploaded Files</h4>
                    </div>
                    <div class="card-body table-responsive">
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
                                            <form action="{{ route('hr.upload.file.delete', $file->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this file?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <p>No files uploaded yet.</p>
            @endif
        </div>
    </div>
@endsection
