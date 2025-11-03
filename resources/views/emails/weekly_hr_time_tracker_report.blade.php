<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Time Tracker Report</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7fa;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f7fa; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="700" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); overflow: hidden;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center;">
                            <div style="font-size: 50px; margin-bottom: 10px;">📊</div>
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 600; letter-spacing: -0.5px;">
                                Weekly Time Tracker Report
                            </h1>
                            <p style="color: #e0e7ff; margin: 10px 0 0 0; font-size: 16px;">
                                Monday – Friday
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            
                            <!-- Summary Stats -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 30px;">
                                <tr>
                                    <td style="text-align: center;">
                                        <table width="100%" cellpadding="0" cellspacing="0" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 10px; padding: 25px 20px;">
                                            <tr>
                                                <td style="text-align: center;">
                                                    <div style="font-size: 40px; margin-bottom: 8px;">⚠️</div>
                                                    <div style="font-size: 32px; font-weight: 700; color: #ffffff; margin-bottom: 8px;">
                                                        {{ count($report) }}
                                                    </div>
                                                    <div style="font-size: 14px; color: #ffffff; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                                        Instances of Incomplete Hours
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Info Message -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff3cd; border-left: 4px solid #ffc107; border-radius: 8px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 18px 20px;">
                                        <p style="margin: 0; color: #856404; font-size: 14px; line-height: 1.6;">
                                            <strong>📋 Report Summary:</strong> The following employees did not complete the required 8 hours of work on the dates indicated below.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            @if(count($report) > 0)
                                <!-- Report Table -->
                                <table width="100%" cellpadding="0" cellspacing="0" style="border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
                                    <thead>
                                        <tr style="background: linear-gradient(to right, #667eea, #764ba2);">
                                            <th style="padding: 16px 12px; text-align: left; font-size: 13px; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 0.5px;">
                                                Employee Name
                                            </th>
                                            <th style="padding: 16px 12px; text-align: left; font-size: 13px; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 0.5px;">
                                                Email Address
                                            </th>
                                            <th style="padding: 16px 12px; text-align: left; font-size: 13px; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 0.5px;">
                                                Date
                                            </th>
                                            <th style="padding: 16px 12px; text-align: left; font-size: 13px; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 0.5px;">
                                                Time Logged
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($report as $index => $entry)
                                            <tr style="background-color: {{ $index % 2 == 0 ? '#ffffff' : '#f9fafb' }};">
                                                <td style="padding: 16px 12px; color: #2d3748; font-size: 14px; font-weight: 600; border-bottom: 1px solid #e2e8f0;">
                                                    {{ $entry['name'] }}
                                                </td>
                                                <td style="padding: 16px 12px; color: #4a5568; font-size: 14px; border-bottom: 1px solid #e2e8f0;">
                                                    {{ $entry['email'] }}
                                                </td>
                                                <td style="padding: 16px 12px; color: #4a5568; font-size: 14px; border-bottom: 1px solid #e2e8f0;">
                                                    📅 {{ \Carbon\Carbon::parse($entry['date'])->format('d F Y') }}
                                                </td>
                                                <td style="padding: 16px 12px; border-bottom: 1px solid #e2e8f0;">
                                                    <span style="display: inline-block; padding: 6px 12px; background-color: #fee2e2; color: #991b1b; border-radius: 6px; font-size: 13px; font-weight: 600;">
                                                        ⏱️ {{ $entry['total_time'] }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <!-- Statistics Summary -->
                                <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 30px; background-color: #f7fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                                    <tr>
                                        <td style="padding: 20px;">
                                            <table width="100%" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td style="padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;">
                                                        <p style="margin: 0; color: #2d3748; font-size: 14px; font-weight: 600;">
                                                            📈 Week Statistics
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-top: 12px;">
                                                        <table width="100%" cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td width="50%" style="padding: 8px 0;">
                                                                    <span style="color: #718096; font-size: 13px;">Total Instances:</span>
                                                                    <strong style="color: #2d3748; font-size: 14px; margin-left: 8px;">{{ count($report) }}</strong>
                                                                </td>
                                                                <td width="50%" style="padding: 8px 0;">
                                                                    <span style="color: #718096; font-size: 13px;">Unique Employees:</span>
                                                                    <strong style="color: #2d3748; font-size: 14px; margin-left: 8px;">
                                                                        {{ collect($report)->unique('email')->count() }}
                                                                    </strong>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" style="padding: 8px 0;">
                                                                    <span style="color: #718096; font-size: 13px;">Report Generated:</span>
                                                                    <strong style="color: #2d3748; font-size: 14px; margin-left: 8px;">{{ now()->format('d M Y, h:i A') }}</strong>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>

                                <!-- Action Required -->
                                <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 25px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px;">
                                    <tr>
                                        <td style="padding: 20px; text-align: center;">
                                            <p style="margin: 0 0 8px 0; color: #ffffff; font-size: 15px; font-weight: 600;">
                                                📢 Action Required
                                            </p>
                                            <p style="margin: 0; color: #e0e7ff; font-size: 13px; line-height: 1.6;">
                                                Please follow up with the mentioned employees to ensure compliance with working hour policies.
                                            </p>
                                        </td>
                                    </tr>
                                </table>

                            @else
                                <!-- Success Message -->
                                <table width="100%" cellpadding="0" cellspacing="0" style="background: linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%); border-radius: 8px; text-align: center;">
                                    <tr>
                                        <td style="padding: 50px 30px;">
                                            <div style="font-size: 60px; margin-bottom: 15px;">🎉</div>
                                            <h2 style="color: #22543d; margin: 0 0 10px 0; font-size: 24px; font-weight: 600;">
                                                Perfect Compliance!
                                            </h2>
                                            <p style="color: #276749; margin: 0; font-size: 16px; line-height: 1.6;">
                                                All employees completed their required 8 hours this week.<br>
                                                Excellent work! 👏
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f7fafc; padding: 25px 30px; text-align: center; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0; color: #718096; font-size: 13px; line-height: 1.6;">
                                This is an automated weekly report from the Time Tracking System.<br>
                                For questions or concerns, please contact the HR department.
                            </p>
                            <p style="margin: 15px 0 0 0; color: #a0aec0; font-size: 12px;">
                                © {{ now()->format('Y') }} Your Company. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>