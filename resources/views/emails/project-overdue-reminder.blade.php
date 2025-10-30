<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Overdue Reminder</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #fef3c7 0%, #fca5a5 50%, #f59e0b 100%);
            margin: 0;
            padding: 40px 20px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #1f2937;
            min-height: 100vh;
        }
        
        .container {
            max-width: 640px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #ffffff;
            padding: 32px 28px;
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 150%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 60%);
            animation: pulse 3s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1) rotate(0deg); opacity: 0.5; }
            50% { transform: scale(1.1) rotate(5deg); opacity: 0.8; }
        }
        
        .header-content {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
            flex: 1;
        }
        
        .alert-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            font-size: 24px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .pill {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.25);
            color: #ffffff;
            font-size: 13px;
            font-weight: 600;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            margin-top: 8px;
        }
        
        .content {
            padding: 32px 28px;
            line-height: 1.7;
        }
        
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 16px;
        }
        
        .content p {
            color: #4b5563;
            font-size: 15px;
            margin-bottom: 16px;
        }
        
        .alert-banner {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border-left: 4px solid #ef4444;
            padding: 16px 20px;
            border-radius: 8px;
            margin: 24px 0;
            display: flex;
            align-items: start;
            gap: 12px;
        }
        
        .alert-banner-icon {
            font-size: 20px;
            flex-shrink: 0;
            margin-top: 2px;
        }
        
        .alert-banner-text {
            font-size: 14px;
            color: #991b1b;
            font-weight: 500;
        }
        
        .details {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 24px 0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }
        
        .details th,
        .details td {
            text-align: left;
            padding: 14px 16px;
            font-size: 14px;
        }
        
        .details th {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            color: #1e293b;
            font-weight: 600;
            width: 40%;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .details td {
            background: #ffffff;
            color: #475569;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .details tr:last-child th,
        .details tr:last-child td {
            border-bottom: none;
        }
        
        .overdue-highlight {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%) !important;
            color: #dc2626 !important;
            font-weight: 700 !important;
            font-size: 15px !important;
        }
        
        .cta {
            margin-top: 28px;
            text-align: center;
        }
        
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.5);
        }
        
        .btn-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        }
        
        .btn-blue:hover {
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.5);
        }
        
        .note {
            margin-top: 24px;
            padding: 16px;
            background: #f8fafc;
            border-radius: 8px;
            color: #64748b;
            font-size: 13px;
            border: 1px solid #e2e8f0;
        }
        
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e2e8f0, transparent);
            margin: 28px 0;
        }
        
        .explanation-box {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #3b82f6;
            margin: 24px 0;
        }
        
        .explanation-title {
            font-size: 15px;
            font-weight: 600;
            color: #1e40af;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .explanation-text {
            color: #1e40af;
            font-size: 14px;
            margin-bottom: 16px;
        }
        
        .explanation-guidelines {
            background: #ffffff;
            padding: 16px;
            border-radius: 8px;
            border: 1px solid #bfdbfe;
        }
        
        .explanation-guidelines p {
            color: #475569;
            font-size: 13px;
            margin-bottom: 12px;
            font-weight: 500;
        }
        
        .explanation-guidelines ul {
            color: #64748b;
            font-size: 13px;
            margin-left: 20px;
            line-height: 1.8;
        }
        
        .footer {
            padding: 24px 28px;
            font-size: 13px;
            color: #6b7280;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            text-align: center;
            line-height: 1.6;
        }
        
        .footer-logo {
            font-weight: 700;
            color: #ef4444;
            margin-bottom: 8px;
        }
        
        @media (max-width: 600px) {
            body {
                padding: 20px 10px;
            }
            
            .container {
                border-radius: 12px;
            }
            
            .header {
                padding: 24px 20px;
            }
            
            .header h1 {
                font-size: 20px;
            }
            
            .content {
                padding: 24px 20px;
            }
            
            .details th,
            .details td {
                padding: 12px;
                font-size: 13px;
            }
            
            .alert-icon {
                width: 40px;
                height: 40px;
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-content">
                <div style="flex: 1;">
                    <h1>⚠️ Project Overdue Reminder</h1>
                    <span class="pill">{{ optional($user->department)->name ?? 'Development' }}</span>
                </div>
                <div class="alert-icon">⏰</div>
            </div>
        </div>
        
        <div class="content">
            <div class="greeting">Hi {{ $user->name }},</div>
            
            <p>
                This is a friendly reminder that your project has crossed the allotted timeline and requires immediate attention.
            </p>

            <div class="alert-banner">
                <div class="alert-banner-icon">🔔</div>
                <div class="alert-banner-text">
                    Action Required: This project is currently <strong>{{ $daysOverdue }} day{{ $daysOverdue > 1 ? 's' : '' }}</strong> overdue and needs your attention.
                </div>
            </div>

            <table class="details">
                <tr>
                    <th>Project Name</th>
                    <td>{{ $project->name }}</td>
                </tr>
                <tr>
                    <th>Department</th>
                    <td>{{ optional($user->department)->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Start Date</th>
                    <td>{{ \Carbon\Carbon::parse($startDate)->toFormattedDateString() }}</td>
                </tr>
                <tr>
                    <th>Allowed Timeline</th>
                    <td>{{ $thresholdMonths }} month{{ $thresholdMonths > 1 ? 's' : '' }}</td>
                </tr>
                <tr>
                    <th>Deadline</th>
                    <td>{{ \Carbon\Carbon::parse($deadlineDate)->toFormattedDateString() }}</td>
                </tr>
                <tr>
                    <th>Days Overdue</th>
                    <td class="overdue-highlight">{{ $daysOverdue }} day{{ $daysOverdue > 1 ? 's' : '' }}</td>
                </tr>
            </table>

            <p>
                Please prioritize completing the remaining work or update the project status with appropriate remarks in the HR portal.
            </p>

            <div class="cta">
                <a class="btn" href="{{ url('/') }}" target="_blank" rel="noopener">Open HR Portal</a>
            </div>

            <div class="divider"></div>

            <div class="explanation-box">
                <div class="explanation-title">
                    <span>📝</span>
                    <span>Explanation Required</span>
                </div>
                <p class="explanation-text">
                    Due to the significant delay in this project, please provide a brief professional explanation to the HR department regarding the reasons for the delay. This will help management understand the circumstances and provide appropriate support if needed.
                </p>
                <div class="explanation-guidelines">
                    <p>Your response should include:</p>
                    <ul>
                        <li>Primary reasons for the project delay</li>
                        <li>Any technical or resource-related challenges faced</li>
                        <li>Current progress status and completion percentage</li>
                        <li>Revised timeline or expected completion date</li>
                        <li>Any support or resources needed to expedite completion</li>
                    </ul>
                </div>
               
            </div>

            <div class="note">
                💡 <strong>Note:</strong> If this project has already been completed or the timeline has been extended, kindly update the status in the portal or ignore this message.
            </div>
        </div>
        
        <div class="footer">
            <div class="footer-logo">Quantum IT Innovation</div>
            <div>Sent automatically by HRM System • Please do not reply to this email</div>
        </div>
    </div>
</body>
</html>