<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Update Project End Date</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .alert { padding:10px; margin-bottom:10px; border-radius:4px; }
        .alert-success { border:1px solid #c3e6cb; background:#d4edda; color:#155724; }
        .alert-info { border:1px solid #bee5eb; background:#d1ecf1; color:#0c5460; }
        .error { color:#b94a48; }
        button { background:#28a745; color:#fff; padding:10px 14px; border:none; border-radius:4px; cursor:pointer; }
        input[type="date"] { padding:8px; margin-top:6px; margin-bottom:10px; }
    </style>
    </head>
<body>
    <h2>Update Project End Date</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    @if($alreadySet)
        <p><strong>Project end date is already set:</strong>
           {{ \Illuminate\Support\Carbon::parse($timeTracker->project_end_date)->toDateString() }}</p>
        <p>This field is read-only.</p>
    @else
        <form action="{{ route('ba.update.project.enddate',  $timeTracker->id) }}" method="POST">
            @csrf
            <label for="project_end_date">Project End Date</label><br>
            <input
                type="date"
                id="project_end_date"
                name="project_end_date"
                required
                value="{{ old('project_end_date') }}"
            >
            @error('project_end_date')
                <div class="error">{{ $message }}</div>
            @enderror

            <button type="submit">Save</button>
        </form>
    @endif
</body>
</html>


