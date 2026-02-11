<!DOCTYPE html>
<html>

<head>
    <title>Deadline Extension Request</title>
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
            background: #ffc107;
            color: black;
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
            border-left: 4px solid #ffc107;
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
            <h1>Deadline Extension Request</h1>
        </div>

        <div class="content">
            <p>Dear Business Analyst,</p>

            <p>HR has approved a delay reason for a project that has passed its deadline. As a result, they are requesting a new deadline and a reason for the delay from your end.</p>

            <div class="details">
                <h3>Project Details:</h3>
                <p><strong>Employee:</strong> {{ $user->name }} {{ $user->lastname }}</p>
                <p><strong>Project:</strong> {{ $project->name ?? 'N/A' }}</p>
                <p><strong>User's Delay Reason:</strong> {{ $timeTracker->status_reason }}</p>
            </div>

            <p>Please click the button below to provide the new deadline and the BA-side delay reason:</p>

            <a href="{{ $updateUrl }}"
                style="background:#28a745;color:#fff;padding:12px 16px;border-radius:4px;text-decoration:none;display:inline-block;">
                Update New Deadline
            </a>

        </div>

        <div class="footer">
            <p>This is an automated notification. Please do not reply to this email.</p>
        </div>
    </div>
</body>

</html>
