<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Report Email Preview</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f0f0;
            padding: 20px;
        }
        .preview-container {
            max-width: 1400px;
            margin: 0 auto;
        }
        .preview-header {
            text-align: center;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .preview-header h1 {
            color: #333;
            margin-bottom: 10px;
        }
        .preview-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 20px;
        }
        .preview-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .preview-section h2 {
            margin-bottom: 15px;
            color: #667eea;
            font-size: 18px;
        }
        .email-body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            line-height: 1.6;
            border-radius: 8px;
        }
        .mail-wrapper {
            max-width: 600px;
            margin: 0 auto;
        }
        .mail-container { 
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px 40px;
            color: #ffffff;
        }
        .header h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 14px;
            opacity: 0.95;
        }
        .content {
            padding: 40px;
        }
        .greeting {
            font-size: 16px;
            color: #333;
            margin-bottom: 25px;
        }
        .alert-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 18px 20px;
            margin: 25px 0;
            border-radius: 6px;
        }
        .alert-box.important {
            background: #f8d7da;
            border-left-color: #dc3545;
        }
        .alert-box p {
            margin: 0;
            color: #856404;
            font-weight: 500;
        }
        .alert-box.important p {
            color: #721c24;
        }
        .info-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
            border: 1px solid #e9ecef;
        }
        .info-section p {
            margin-bottom: 12px;
            color: #495057;
        }
        .info-section p:last-child {
            margin-bottom: 0;
        }
        .info-section.warning {
            background: #fff5f5;
            border-color: #fed7d7;
        }
        .info-section.warning strong {
            color: #c53030;
        }
        .info-section.warning p {
            color: #742a2a;
        }
        .highlight {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 18px 20px;
            margin: 25px 0;
            border-radius: 6px;
        }
        .highlight strong {
            color: #1976D2;
            display: block;
            margin-bottom: 8px;
            font-size: 15px;
        }
        .highlight p {
            margin: 0;
            color: #424242;
        }
        .deadline {
            display: inline-block;
            background: #667eea;
            color: #ffffff;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 15px;
            margin: 10px 0;
        }
        .signature {
            margin-top: 40px;
            padding-top: 25px;
            border-top: 2px solid #e9ecef;
            color: #666;
        }
        .signature strong {
            color: #333;
            display: block;
            margin-top: 5px;
        }
        .footer {
            background: #f8f9fa;
            padding: 25px 40px;
            text-align: center;
            font-size: 13px;
            color: #6c757d;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 5px 0;
        }
        .content p {
            margin-bottom: 15px;
            color: #555;
        }
        .content ul {
            margin-left: 20px;
            margin-top: 12px;
        }
        .content ul li {
            margin-bottom: 8px;
            color: #495057;
        }
        @media only screen and (max-width: 1200px) {
            .preview-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="preview-container">
       

        <div class="preview-grid">
            <!-- Version 1: Regular Reminder -->
            <div class="preview-section">
               
                <div class="email-body">
                    <div class="mail-wrapper">
                        <div class="mail-container">
                            <div class="header">
                                <h1>📊 Weekly Report Reminder</h1>
                                <p>Important: Action Required</p>
                            </div>
                            
                            <div class="content">
                                <div class="greeting">
                                    <p><strong>Hello Team Member,</strong></p>
                                    <p>Greetings of the day!</p>
                                </div>

                                <div class="alert-box">
                                    <p>📅 You are required to submit your weekly report by <strong>today, latest by 8:00 PM</strong>.</p>
                                </div>

                                <p>Submission Deadline: <span class="deadline">Today by 8:00 PM</span></p>

                                <div class="info-section">
                                    <p><strong>📋 Importance of Weekly Reports:</strong></p>
                                    <p>As per company policy, timely submission of weekly reports is mandatory for all team members. These reports serve multiple critical purposes:</p>
                                    <ul>
                                        <li>Tracking individual and team performance</li>
                                        <li>Planning and prioritizing upcoming tasks</li>
                                        <li>Maintaining transparency in work progress</li>
                                        <li>Facilitating effective communication across teams</li>
                                    </ul>
                                </div>

                                <div class="info-section warning">
                                    <p><strong>⚠️ Important Notice:</strong></p>
                                    <p style="color: red; font-weight: bold;">IN CASE THE REPORT IS NOT SUBMITTED WITHIN THE GIVEN TIMELINE, THE SALARY FOR THAT PARTICULAR WEEK WILL BE PUT ON HOLD.</p>
                                </div>

                                <p style="margin-top: 25px;">We request you to adhere strictly to the reporting timelines and ensure compliance with the company's process to avoid any inconvenience.</p>

                                <div class="highlight">
                                    <strong>📌 NOTE </strong>
                                    <p>Employees who are on leave on Friday are requested to submit their weekly report on Thursday by EOD (8:00 PM).</p>
                                </div>

                                <div class="signature">
                                    <p>Thank you for your cooperation.</p>
                                    <p><strong>Best regards,<br>Human Resources Team</strong></p>
                                </div>
                            </div>

                            <div class="footer">
                                <p>This is an automated reminder. Please do not reply to this email.</p>
                                <p>For any queries, please contact HR department.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

          
          
        </div>
    </div>
</body>
</html>