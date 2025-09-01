<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .sender-info {
            display: flex;
            align-items: center;
            background-color: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid #dee2e6;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #007bff;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 15px;
        }

        .sender-details h4 {
            margin: 0;
            color: #333;
        }

        .sender-details p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }

        .time {
            margin-left: auto;
            color: #666;
            font-size: 12px;
        }

        .action-section {
            background-color: #e3f2fd;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
        }

        .action-buttons {
            margin-top: 15px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin: 0 10px;
            display: inline-block;
        }

        .btn-approve {
            background-color: #28a745;
            color: white;
        }

        .btn-decline {
            background-color: #dc3545;
            color: white;
        }

        .content {
            padding: 20px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #f8f9fa;
        }

        .details-table td {
            padding: 12px;
            border: 1px solid #dee2e6;
        }

        .details-table td:first-child {
            font-weight: bold;
            background-color: #e9ecef;
            width: 30%;
        }

        .reason-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .reason-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .reason-content {
            background-color: white;
            padding: 15px;
            border-radius: 3px;
            font-style: italic;
            color: #555;
        }

        .footer {
            padding: 20px;
            text-align: center;
            background-color: #f8f9fa;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h2>Leave Request: {{ $details['subject'] }}</h2>
        </div>

        <!-- Sender Info -->
        <div class="sender-info">
            <div class="avatar">
                {{ substr($details['name'], 0, 1) }}
            </div>
            <div class="sender-details">
                <h4>{{ $details['name'] }}</h4>
                <p>{{ $details['employee_email'] ?? 'Employee' }}</p>
            </div>
            <div class="time">
                Now
            </div>
        </div>

        <!-- Action Required Section -->
        <div class="action-section">
            <h3 style="margin: 0; color: #007bff;">Manager Action Required</h3>
            <p style="margin: 10px 0;">Please review the leave request below and take appropriate action:</p>
            <div class="action-buttons">
                <a href="{{ $details['approve_url'] }}" class="btn btn-approve">✓ Accept</a>
                <a href="{{ $details['disapprove_url'] }}" class="btn btn-decline">✗ Decline</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="content">
            <p><strong>Dear Manager,</strong></p>
            <p>I am writing to formally request leave from work. Please find the details of my leave request below:</p>

            <!-- Leave Request Details Table -->
            <table class="details-table">
                <tr>
                    <td>Employee Name</td>
                    <td>{{ $details['name'] }}</td>
                </tr>
                <tr>
                    <td>Employee ID</td>
                    <td>{{ $details['employee_id'] ?? 'EMP001' }}</td>
                </tr>
                <tr>
                    <td>Leave Type</td>
                    <td>{{ $details['type'] }}</td>
                </tr>
                <tr>
                    <td>Duration</td>
                    <td>{{ $details['duration'] ?? '1 day(s)' }}</td>
                </tr>
                <tr>
                    <td>Start Date</td>
                    <td>{{ date('l, F j, Y', strtotime($details['start_date'])) }}</td>
                </tr>
                <tr>
                    <td>End Date</td>
                    <td>{{ date('l, F j, Y', strtotime($details['end_date'])) }}</td>
                </tr>
            </table>

            <!-- Reason for Leave -->
            <div class="reason-section">
                <div class="reason-title">Reason for Leave:</div>
                <div class="reason-content">
                    {{ $details['description'] }}
                </div>
            </div>

            <!-- Professional Closing -->
            <p>I have ensured that all my current projects and responsibilities are either completed or properly
                delegated to team members. I will be available for any urgent matters via email or phone if needed.</p>

            <p>I would appreciate your approval for this leave request. Please let me know if you need any additional
                information or documentation.</p>

            <p>Thank you for your consideration.</p>

            <p><strong>Best regards,</strong><br>
                {{ $details['name'] }}<br>
                <span style="color: #666;">{{ $details['designation'] ?? 'Employee' }}</span><br>
                <span style="color: #666;">Employee ID: {{ $details['employee_id'] ?? 'EMP001' }}</span><br>
                <span style="color: #666;">Contact: {{ $details['contact'] ?? '+1 (555) 123-4567' }}</span>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This is an automated email. Please do not reply to this email address.</p>
        </div>
    </div>
</body>

</html>
