<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Logout Report</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7fa;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f7fa; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); overflow: hidden;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 600; letter-spacing: -0.5px;">
                                📋 Daily Logout Report
                            </h1>
                            <p style="color: #e0e7ff; margin: 10px 0 0 0; font-size: 16px;">
                                {{ now()->format('l, F d, Y') }}
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            @if(count($pendingUsers) > 0)
                                <!-- Alert Box -->
                                <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff3cd; border-left: 4px solid #ffc107; border-radius: 6px; margin-bottom: 30px;">
                                    <tr>
                                        <td style="padding: 15px 20px;">
                                            <p style="margin: 0; color: #856404; font-size: 14px; line-height: 1.6;">
                                                <strong>⚠️ Attention Required:</strong> {{ count($pendingUsers) }} employee(s) have not logged out yet.
                                            </p>
                                        </td>
                                    </tr>
                                </table>

                                <h2 style="color: #2d3748; font-size: 20px; margin: 0 0 20px 0; font-weight: 600;">
                                    Pending Logout Employees
                                </h2>

                                <!-- Employee Table -->
                                <table width="100%" cellpadding="0" cellspacing="0" style="border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
                                    <thead>
                                        <tr style="background-color: #f7fafc;">
                                            <th style="padding: 16px 12px; text-align: left; font-size: 13px; font-weight: 600; color: #4a5568; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0;">
                                                Name
                                            </th>
                                            <th style="padding: 16px 12px; text-align: left; font-size: 13px; font-weight: 600; color: #4a5568; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0;">
                                                Email
                                            </th>
                                            <th style="padding: 16px 12px; text-align: left; font-size: 13px; font-weight: 600; color: #4a5568; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0;">
                                                Check-in Time
                                            </th>
                                            <th style="padding: 16px 12px; text-align: left; font-size: 13px; font-weight: 600; color: #4a5568; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0;">
                                                Location
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendingUsers as $index => $user)
                                            <tr style="background-color: {{ $index % 2 == 0 ? '#ffffff' : '#f9fafb' }};">
                                                <td style="padding: 16px 12px; color: #2d3748; font-size: 14px; border-bottom: 1px solid #e2e8f0;">
                                                    <strong>{{ $user->name }} {{ $user->lastname }}</strong>
                                                </td>
                                                <td style="padding: 16px 12px; color: #4a5568; font-size: 14px; border-bottom: 1px solid #e2e8f0;">
                                                    {{ $user->email }}
                                                </td>
                                                <td style="padding: 16px 12px; color: #4a5568; font-size: 14px; border-bottom: 1px solid #e2e8f0;">
                                                    🕐 {{ $user->start_time }}
                                                </td>
                                                <td style="padding: 16px 12px; color: #4a5568; font-size: 14px; border-bottom: 1px solid #e2e8f0;">
                                                    📍 {{ $user->start_time_location }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <!-- Summary Box -->
                                <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 25px; background-color: #f7fafc; border-radius: 6px; border: 1px solid #e2e8f0;">
                                    <tr>
                                        <td style="padding: 15px 20px;">
                                            <p style="margin: 0; color: #4a5568; font-size: 13px; line-height: 1.6;">
                                                <strong>Total Pending:</strong> {{ count($pendingUsers) }} employee(s)<br>
                                                <strong>Report Generated:</strong> {{ now()->format('h:i A') }}
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
                                                Perfect Attendance!
                                            </h2>
                                            <p style="color: #276749; margin: 0; font-size: 16px; line-height: 1.6;">
                                                All employees have successfully logged out today.<br>
                                                Great job, team! 👏
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
                                This is an automated report from the Attendance Management System.<br>
                                If you have any questions, please contact HR department.
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