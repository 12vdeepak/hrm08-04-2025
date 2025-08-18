<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRM Tracking Alert</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #dc3545;
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: -30px -30px 20px -30px;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
        }

        .alert-icon {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .content {
            margin: 20px 0;
        }

        .greeting {
            margin-bottom: 20px;
        }

        .message {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
        }

        .footer {
            border-top: 1px solid #eee;
            padding-top: 20px;
            text-align: left;
            color: #333;
            margin-top: 30px;
        }

        .important {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="alert-icon">⚠️</div>
            <h1>HRM Tracking Alert - Immediate Action Required</h1>
        </div>

        <div class="content">
            <div class="greeting">
                <strong>Dear {{ $user->name }} {{ $user->lastname }},</strong>
            </div>

            <div class="message">
                <p>This is to inform you that your activity time is reflecting as less than your total login time
                    because the HRM application is not being kept open in your browser during office hours.</p>
            </div>

            <p>To ensure accurate attendance tracking and avoid any issues related to salary processing or work
                compliance in the future, we request you to keep HRM open in your browser throughout office hours.</p>

            <p>Your cooperation in this matter will help us maintain accurate records.</p>
        </div>

        <div class="footer">
            <p><strong>Thanks</strong><br>
                HR Team</p>
        </div>
    </div>
</body>

</html>
