<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $announcement->title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #1a202c;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            min-height: 100vh;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }
        
        .title {
            font-size: 28px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 12px;
            position: relative;
            z-index: 1;
            letter-spacing: -0.5px;
        }
        
        .department {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-size: 13px;
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 500;
            position: relative;
            z-index: 1;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .content {
            padding: 40px 30px;
            background: #ffffff;
        }
        
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 20px;
        }
        
        .announcement-body {
            color: #4a5568;
            font-size: 15px;
            line-height: 1.8;
            margin-bottom: 24px;
        }
        
        .announcement-body p {
            margin-bottom: 16px;
        }
        
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e2e8f0, transparent);
            margin: 30px 0;
        }
        
        .closing {
            color: #4a5568;
            font-size: 15px;
        }
        
        .action-section {
            background: linear-gradient(135deg, #f6f8fb 0%, #e9ecef 100%);
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        
        .btn {
            display: inline-block;
            padding: 14px 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        
        .footer {
            background: #f7fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        
        .footer-logo {
            font-size: 16px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 12px;
        }
        
        .footer-text {
            color: #718096;
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 8px;
        }
        
        .social-links {
            margin-top: 20px;
        }
        
        .social-links a {
            display: inline-block;
            width: 36px;
            height: 36px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            margin: 0 6px;
            line-height: 36px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            transform: translateY(-3px);
            background: #764ba2;
        }
        
        @media (max-width: 600px) {
            body {
                padding: 20px 10px;
            }
            
            .header, .content, .action-section, .footer {
                padding: 30px 20px;
            }
            
            .title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="title">{{ $announcement->title }}</div>
            <div class="department">{{ $announcement->department }}</div>
        </div>

        <div class="content">
            <div class="greeting">Hello {{ $name }},</div>
            
            <div class="announcement-body">
                {!! $announcement->announcement !!}
            </div>
            
            <div class="divider"></div>
            
            <div class="closing">
                Thank you for your attention.
            </div>
        </div>

        <div class="footer">
            <div class="footer-logo">Quantum IT Innovation</div>
            <p class="footer-text">This is an automated announcement from our HRM System.</p>
            <p class="footer-text">Please do not reply to this email.</p>
            
            
        </div>
    </div>
</body>
</html>