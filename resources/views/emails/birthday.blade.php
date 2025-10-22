<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            padding: 20px;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .birthday-image {
            width: 100%;
            height: 350px;
            object-fit: cover;
            display: block;
        }

        .content-section {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 28px;
            color: #2c3e50;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        .birthday-icon {
            font-size: 48px;
            text-align: center;
            margin-bottom: 15px;
        }

        .message-text {
            font-size: 16px;
            color: #555;
            text-align: center;
            margin-bottom: 25px;
            line-height: 1.8;
        }

        .celebration-box {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            border-radius: 15px;
            padding: 25px;
            margin: 25px 0;
            border-left: 5px solid #f5576c;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .celebration-box h3 {
            color: #d63031;
            font-size: 18px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .celebration-box p {
            color: #2d3436;
            font-size: 15px;
            margin: 8px 0;
        }

        .date-highlight {
            background: #ffffff;
            padding: 12px;
            border-radius: 8px;
            margin-top: 10px;
            font-weight: bold;
            color: #f5576c;
            text-align: center;
            font-size: 18px;
        }

        .wishes-section {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            padding: 30px;
            text-align: center;
            border-radius: 15px;
            margin: 25px 0;
        }

        .wishes-text {
            font-size: 18px;
            color: #2c3e50;
            font-style: italic;
            font-weight: 500;
            line-height: 1.8;
        }

        .decorative-divider {
            text-align: center;
            margin: 25px 0;
            font-size: 30px;
            opacity: 0.6;
        }

        .signature-section {
            text-align: center;
            margin-top: 35px;
            padding-top: 25px;
            border-top: 2px dashed #ddd;
        }

        .regards {
            font-size: 16px;
            color: #666;
            margin-bottom: 8px;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #7f8c8d;
            font-size: 13px;
        }

        .festive-border {
            height: 8px;
            background: linear-gradient(90deg,
                    #ff6b6b 0%,
                    #f093fb 25%,
                    #4facfe 50%,
                    #ffd93d 75%,
                    #6bcf7f 100%);
        }

        .name-highlight {
            color: #f5576c;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="festive-border"></div>

        <!-- Birthday Image -->
        <img src="{{ asset('images/birthday/birthday.jpg') }}" alt="Happy Birthday" class="birthday-image">

        <div class="content-section">
            <div class="birthday-icon">🎂🎉🎈</div>
            
            <div class="greeting">
                Happy Birthday, <span class="name-highlight">{{ $name }}</span>!
            </div>

            <div class="message-text">
                On this special day, the entire team at <strong>{{ $companyName }}</strong> 
                wants to celebrate YOU! May your birthday be filled with laughter, joy, 
                and countless beautiful moments that you'll cherish forever.
            </div>

            <div class="decorative-divider">✦ ✦ ✦</div>

            <div class="celebration-box">
                <h3>🎊 Today We Celebrate You!</h3>
                <p>Your dedication, hard work, and positive spirit make our workplace brighter every day.</p>
                <div class="date-highlight">
                    📅 {{ \Carbon\Carbon::parse($birthday)->format('l, F j') }}
                </div>
                <p style="margin-top: 15px; font-size: 14px;">
                    Thank you for being an invaluable part of our team. We're so grateful to have you with us!
                </p>
            </div>

            <div class="wishes-section">
                <div class="wishes-text">
                    May this year bring you success in everything you do, 
                    adventures that excite you, and moments that make you smile. 
                    Here's to another year of achieving great things together! 🌟
                </div>
            </div>

            <div class="signature-section">
                <div class="regards">Warmest Birthday Wishes,</div>
                <div class="company-name">Quantum IT Innovation</div>
            </div>
        </div>

        <div class="footer">
            This is an automated birthday greeting from Quantum IT Innovation.<br>
            Wishing you a fantastic year ahead! 🎂
        </div>

        <div class="festive-border"></div>
    </div>
</body>

</html>