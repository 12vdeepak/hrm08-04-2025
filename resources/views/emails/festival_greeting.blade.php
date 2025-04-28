{{-- resources/views/emails/festival_reminder.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <title>Festival Reminder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
        }

        .important {
            color: #d9534f;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>{{ $festivalName }} Holiday Reminder</h1>
        </div>

        <p>Dear {{ $employeeName }},</p>

        <p>This is a friendly reminder that our office will be <span class="important">closed tomorrow</span> for
            {{ $festivalName }} celebrations.</p>

        <p>The holiday period will be from {{ $startDate }} to {{ $endDate }}.</p>

        <p>Please ensure you complete any pending tasks before leaving today, and set appropriate out-of-office messages
            if needed.</p>

        <p>Wishing you a wonderful {{ $festivalName }} celebration with your family and loved ones!</p>

        <p>Best regards,<br>
            Quantum IT Innovation</p>
    </div>
</body>

</html>
