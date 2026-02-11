<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Project Start Date</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .card {
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 600px;
        }
        .card-header {
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 1rem;
        }
        .card-title {
            margin: 0;
            color: #333;
            font-size: 1.5rem;
        }
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .details-section {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #007bff;
        }
        .details-section h5 {
            margin-top: 0;
            color: #666;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #444;
        }
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-control:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
        }
        .is-invalid {
            border-color: #dc3545;
        }
        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            text-align: center;
            border: none;
            transition: background-color 0.2s;
        }
        .btn-primary {
            background-color: #007bff;
            color: #fff;
        }
        .btn-primary:hover {
            background-color: #0069d9;
        }
        .text-end {
            text-align: right;
        }
        .text-danger {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Update Project Start Date</h4>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="details-section">
                <h5>Time Tracker Details</h5>
                <p><strong>Employee:</strong> {{ $timeTracker->user->name }}</p>
                <p><strong>Project:</strong> {{ $timeTracker->project?->name ?? 'N/A' }}</p>
                <p><strong>Work Date:</strong> {{ $timeTracker->work_date }}</p>
                <p><strong>Work Description:</strong> {{ $timeTracker->work_title }}</p>
            </div>

            <form action="{{ route('ba.update.project.date', $timeTracker->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Project Start Date <span class="text-danger">*</span></label>
                    <input type="date" name="project_start_date" class="form-control @error('project_start_date') is-invalid @enderror" value="{{ old('project_start_date', $timeTracker->project_start_date) }}" required>
                    @error('project_start_date')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Update Start Date</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
