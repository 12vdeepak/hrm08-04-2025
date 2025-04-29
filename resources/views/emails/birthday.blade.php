<!DOCTYPE html>
<html>
<head>
    <title>Happy Birthday!</title>
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
            text-align: center;
            padding: 20px 0;
            background-color: #f8f9fa;
            border-radius: 5px 5px 0 0;
        }
        .header h1 {
            color: #5c6bc0;
        }
        .content {
            padding: 20px;
        }
        .footer {
            text-align: center;
            padding: 20px 0;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
        }
        .gift-img {
            display: block;
            margin: 0 auto;
            max-width: 150px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Happy Birthday, {{ $user->name }}! 🎉</h1>
        </div>
        <div class="content">
            <p>Dear {{ $user->name }},</p>
            
            <p>On behalf of the entire team, we'd like to wish you a very happy birthday! May your day be filled with joy, laughter, and wonderful moments.</p>
            
            <p>Thank you for being an essential part of our team. Your contributions and dedication are truly valued.</p>
            
            <p>Enjoy your special day!</p>
            
            <p>Warm regards,<br>
            The Management Team</p>
        </div>
        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
        </div>
    </div>
</body>
</html>