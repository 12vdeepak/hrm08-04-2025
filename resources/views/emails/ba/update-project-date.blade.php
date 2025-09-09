<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Update Project Start Date</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h2>Update Project Start Date</h2>

    @if(session('success'))
        <div style="padding:10px;border:1px solid #cce5ff;background:#e9f7ff;margin-bottom:10px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('info'))
        <div style="padding:10px;border:1px solid #ffeeba;background:#fff3cd;margin-bottom:10px;">
            {{ session('info') }}
        </div>
    @endif

    @if($alreadySet)
        <p><strong>Project start date is already set:</strong>
           {{ \Illuminate\Support\Carbon::parse($timeTracker->project_start_date)->toDateString() }}</p>
        <p>This field is read-only.</p>
    @else
            <form action="{{ route('ba.update.project.date',  $timeTracker->id) }}" method="POST">
                @csrf
                <label for="project_start_date">Project Start Date</label><br>
                <input
                    type="date"
                    id="project_start_date"
                    name="project_start_date"
                    required
                    value="{{ old('project_start_date') }}"
                    style="padding:8px;margin-top:6px;margin-bottom:10px;"
                >
                @error('project_start_date')
                    <div style="color:#b94a48;">{{ $message }}</div>
                @enderror

                <button type="submit" style="background:#28a745;color:#fff;padding:10px 14px;border:none;border-radius:4px;cursor:pointer;">
                    Save
                </button>
            </form>
    @endif
</body>
</html>
