<!-- resources/views/emails/birthday.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Happy Birthday!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
        }
        .container {
            padding: 20px;
        }
        .header {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .highlight {
            color: #3490dc;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            text-align: center;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🎉 Happy Birthday, {{ $userName }}! 🎂</h2>
        </div>
        
        <div class="content">
            <p>Dear <span class="highlight">{{ $userName }}</span>,</p>
            
            <p>On behalf of everyone at <strong>{{ $companyName }}</strong>, we'd like to wish you a very happy birthday!</p>
            
            <p>May your day be filled with joy, laughter, and wonderful memories. Your contributions to our team are truly valued, and we're fortunate to have you as part of our family.</p>
            
            <p>We hope this year brings you success, happiness, and all the things you wish for.</p>
            
            <p>Enjoy your special day!</p>
            
            <p>
                Warm regards,<br>
                The {{ $companyName }} Team
            </p>
        </div>
        
        <div class="footer">
            <p>This is an automated message from {{ $companyName }}.</p>
        </div>
    </div>
</body>
</html>