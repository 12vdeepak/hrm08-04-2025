{{-- resources/views/emails/festival_greeting.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <title>Festival Greetings</title>
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
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Happy {{ $festivalName }}!</h1>
        </div>

        <p>Dear {{ $employeeName }},</p>

        <p>Wishing you and your family a joyful {{ $festivalName }}. May this festival bring happiness and prosperity to
            your life.</p>

        <p>Please note our office will be closed from {{ $startDate }} to {{ $endDate }} for the celebrations.
        </p>

        <p>Best regards,<br>
            Quantum IT Innovation</p>
    </div>
</body>

</html>
