<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Attendance Report</title>
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
                                📊 Daily Attendance Report
                            </h1>
                            <p style="color: #e0e7ff; margin: 10px 0 0 0; font-size: 16px;">
                                {{ now()->format('l, F d, Y') }}
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            
                            <!-- Summary Stats -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 30px;">
                                <tr>
                                    <td width="50%" style="padding-right: 10px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%); border-radius: 8px; text-align: center; padding: 20px 15px;">
                                            <tr>
                                                <td>
                                                    <div style="font-size: 32px; margin-bottom: 5px;">🚫</div>
                                                    <div style="font-size: 28px; font-weight: 700; color: #ffffff; margin-bottom: 5px;">
                                                        {{ count($absentUsers) }}
                                                    </div>
                                                    <div style="font-size: 13px; color: #ffe0e0; text-transform: uppercase; letter-spacing: 0.5px;">
                                                        Absent
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="50%" style="padding-left: 10px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 8px; text-align: center; padding: 20px 15px;">
                                            <tr>
                                                <td>
                                                    <div style="font-size: 32px; margin-bottom: 5px;">📝</div>
                                                    <div style="font-size: 28px; font-weight: 700; color: #ffffff; margin-bottom: 5px;">
                                                        {{ count($leaveUsers) }}
                                                    </div>
                                                    <div style="font-size: 13px; color: #e0f7ff; text-transform: uppercase; letter-spacing: 0.5px;">
                                                        On Leave
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Absent Users Section -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 30px;">
                                <tr>
                                    <td style="background: linear-gradient(to right, #ff6b6b, #ee5a6f); padding: 15px 20px; border-radius: 8px 8px 0 0;">
                                        <h2 style="color: #ffffff; font-size: 18px; margin: 0; font-weight: 600; display: flex; align-items: center;">
                                            🚫 Absent Employees
                                        </h2>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #e2e8f0; border-top: none; border-radius: 0 0 8px 8px; padding: 20px; background-color: #ffffff;">
                                        @forelse($absentUsers as $index => $user)
                                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: {{ $loop->last ? '0' : '12px' }};">
                                                <tr>
                                                    <td style="padding: 15px; background-color: #fff5f5; border-left: 4px solid #ff6b6b; border-radius: 6px;">
                                                        <table width="100%" cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td width="40" style="vertical-align: top;">
                                                                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #ff6b6b, #ee5a6f); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 16px;">
                                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                                    </div>
                                                                </td>
                                                                <td style="padding-left: 15px; vertical-align: middle;">
                                                                    <div style="font-size: 15px; font-weight: 600; color: #2d3748; margin-bottom: 4px;">
                                                                        {{ $user->name }} {{ $user->lastname }}
                                                                    </div>
                                                                    <div style="font-size: 13px; color: #718096;">
                                                                        📧 {{ $user->email }}
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        @empty
                                            <table width="100%" cellpadding="0" cellspacing="0" style="background: linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%); border-radius: 8px; text-align: center;">
                                                <tr>
                                                    <td style="padding: 30px 20px;">
                                                        <div style="font-size: 48px; margin-bottom: 10px;">🎉</div>
                                                        <p style="color: #22543d; margin: 0; font-size: 16px; font-weight: 600;">
                                                            Perfect Attendance!
                                                        </p>
                                                        <p style="color: #276749; margin: 8px 0 0 0; font-size: 14px;">
                                                            No one is absent today
                                                        </p>
                                                    </td>
                                                </tr>
                                            </table>
                                        @endforelse
                                    </td>
                                </tr>
                            </table>

                            <!-- Leave Users Section -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="background: linear-gradient(to right, #4facfe, #00f2fe); padding: 15px 20px; border-radius: 8px 8px 0 0;">
                                        <h2 style="color: #ffffff; font-size: 18px; margin: 0; font-weight: 600; display: flex; align-items: center;">
                                            📝 Employees on Approved Leave
                                        </h2>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #e2e8f0; border-top: none; border-radius: 0 0 8px 8px; padding: 20px; background-color: #ffffff;">
                                        @forelse($leaveUsers as $index => $user)
                                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: {{ $loop->last ? '0' : '12px' }};">
                                                <tr>
                                                    <td style="padding: 15px; background-color: #f0f9ff; border-left: 4px solid #4facfe; border-radius: 6px;">
                                                        <table width="100%" cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td width="40" style="vertical-align: top;">
                                                                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #4facfe, #00f2fe); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 16px;">
                                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                                    </div>
                                                                </td>
                                                                <td style="padding-left: 15px; vertical-align: middle;">
                                                                    <div style="font-size: 15px; font-weight: 600; color: #2d3748; margin-bottom: 4px;">
                                                                        {{ $user->name }} {{ $user->lastname }}
                                                                    </div>
                                                                    <div style="font-size: 13px; color: #718096;">
                                                                        📧 {{ $user->email }}
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        @empty
                                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f0f9ff; border-radius: 8px; text-align: center;">
                                                <tr>
                                                    <td style="padding: 30px 20px;">
                                                        <div style="font-size: 48px; margin-bottom: 10px;">✅</div>
                                                        <p style="color: #1e40af; margin: 0; font-size: 16px; font-weight: 600;">
                                                            All Hands on Deck!
                                                        </p>
                                                        <p style="color: #3b82f6; margin: 8px 0 0 0; font-size: 14px;">
                                                            No one on leave today
                                                        </p>
                                                    </td>
                                                </tr>
                                            </table>
                                        @endforelse
                                    </td>
                                </tr>
                            </table>

                            <!-- Overall Summary -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 30px; background-color: #f7fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;">
                                                    <p style="margin: 0; color: #2d3748; font-size: 14px; font-weight: 600;">
                                                        📈 Today's Summary
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top: 12px;">
                                                    <table width="100%" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td width="50%" style="padding: 8px 0;">
                                                                <span style="color: #718096; font-size: 13px;">Total Absent:</span>
                                                                <strong style="color: #2d3748; font-size: 14px; margin-left: 8px;">{{ count($absentUsers) }}</strong>
                                                            </td>
                                                            <td width="50%" style="padding: 8px 0;">
                                                                <span style="color: #718096; font-size: 13px;">Total On Leave:</span>
                                                                <strong style="color: #2d3748; font-size: 14px; margin-left: 8px;">{{ count($leaveUsers) }}</strong>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" style="padding: 8px 0;">
                                                                <span style="color: #718096; font-size: 13px;">Report Generated:</span>
                                                                <strong style="color: #2d3748; font-size: 14px; margin-left: 8px;">{{ now()->format('h:i A') }}</strong>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

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