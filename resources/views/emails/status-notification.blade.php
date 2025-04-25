<!DOCTYPE html>
<html>

<head>
    <title>Status Notification</title>
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
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .header {
            background-color: #f5f5f5;
            padding: 10px;
            border-radius: 3px;
            margin-bottom: 20px;
        }

        .status {
            font-weight: bold;
            color: #007bff;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Status Update Notification</h1>
        </div>

        <p>Hello {{ $userData['name'] }},</p>

        <p>This is a notification regarding your current status:</p>

        <p>Your status is: <span class="status">{{ $userData['status'] }}</span></p>

        @if (!empty($userData['timestamp']))
            <p>Status last updated at:
                <strong>{{ \Carbon\Carbon::parse($userData['timestamp'])->format('F j, Y, g:i A') }}</strong></p>
        @endif

        <p>If you believe this status update is incorrect, please contact the administrator.</p>

        <div class="footer">
            <p>Thank you,<br>Your Application Team</p>
            <p>This is an automated email. Please do not reply to this message.</p>
        </div>
    </div>
</body>

</html>
