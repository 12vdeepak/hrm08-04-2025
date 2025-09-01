<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Action Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
        }

        .success {
            color: #28a745;
        }

        .error {
            color: #dc3545;
        }

        .icon {
            font-size: 60px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="icon {{ $success ? 'success' : 'error' }}">
            {{ $success ? '✓' : '✗' }}
        </div>
        <h2 class="{{ $success ? 'success' : 'error' }}">
            {{ $success ? 'Success!' : 'Error!' }}
        </h2>
        <p>{{ $message }}</p>

        @if ($success && isset($leave))
            <div style="margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 5px;">
                <p><strong>Leave Details:</strong></p>
                <p>Employee: {{ $leave->user->name ?? 'N/A' }}</p>
                <p>Type: {{ $leave->type }}</p>
                <p>Dates: {{ $leave->start_date }} to {{ $leave->end_date }}</p>
                <p>Status: {{ $leave->status }}</p>
            </div>
        @endif
    </div>
</body>

</html>
