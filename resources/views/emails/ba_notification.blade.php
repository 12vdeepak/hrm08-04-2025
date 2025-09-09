<!-- resources/views/emails/ba_notification.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <title>Project Start Date Required</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .content {
            padding: 20px;
            background: #f8f9fa;
        }

        .details {
            background: white;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #007bff;
        }

        .button {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            margin: 15px 0;
        }

        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Project Start Date Required</h1>
        </div>

        <div class="content">
            <p>Dear Business Analyst,</p>

            <p>A new time tracker entry has been submitted that requires a project start date to be filled in.</p>

            <div class="details">
                <h3>Time Tracker Details:</h3>
                <p><strong>Employee:</strong> {{ $user->name }}</p>
                <p><strong>Department:</strong> {{ $user->department->name ?? 'N/A' }}</p>
                <p><strong>Project:</strong> {{ $project->name ?? 'N/A' }}</p>
                <p><strong>Work Date:</strong> {{ $timeTracker->work_date }}</p>
                <p><strong>Work Description:</strong> {{ $timeTracker->work_title }}</p>
                <p><strong>Hours:</strong> {{ $timeTracker->work_time }}</p>
            </div>

            <p>Please click the button below to access the system and fill in the project start date:</p>

            {{-- Simple link (requires BA to be logged in if route has auth middleware) --}}
            <a href="{{ route('ba.update.project.date.form', ['timeTracker' => $timeTracker->id]) }}"
                style="background:#28a745;color:#fff;padding:12px 16px;border-radius:4px;text-decoration:none;display:inline-block;">
                Update Project Start Date
            </a>



        </div>

        <div class="footer">
            <p>This is an automated notification. Please do not reply to this email.</p>
        </div>
    </div>
</body>

</html>
