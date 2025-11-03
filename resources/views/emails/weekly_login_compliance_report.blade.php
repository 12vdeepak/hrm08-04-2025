@php
    $reportRows = $reportRows ?? [];
    $reportMessage = $reportMessage ?? '';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Login Compliance Report</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7fa;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f7fa; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="750" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); overflow: hidden;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center;">
                            <div style="font-size: 50px; margin-bottom: 10px;">📋</div>
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 600; letter-spacing: -0.5px;">
                                Weekly Login Compliance Report
                            </h1>
                            <p style="color: #e0e7ff; margin: 10px 0 0 0; font-size: 16px;">
                                {{ now()->format('d M Y') }}
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 35px;">
                            
                            <!-- Greeting -->
                            <p style="margin: 0 0 25px 0; color: #2d3748; font-size: 16px; line-height: 1.6;">
                                Dear <strong>HR Team</strong>,
                            </p>

                            <!-- Summary Stats -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 30px;">
                                <tr>
                                    <td style="text-align: center;">
                                        <table width="100%" cellpadding="0" cellspacing="0" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 10px; padding: 25px 20px;">
                                            <tr>
                                                <td style="text-align: center;">
                                                    <div style="font-size: 40px; margin-bottom: 8px;">📊</div>
                                                    <div style="font-size: 32px; font-weight: 700; color: #ffffff; margin-bottom: 8px;">
                                                        {{ count($reportRows) }}
                                                    </div>
                                                    <div style="font-size: 14px; color: #ffffff; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                                        Total Compliance Records
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Report Message -->
                            @if($reportMessage)
                                <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #e0f2fe; border-left: 4px solid #0284c7; border-radius: 8px; margin-bottom: 30px;">
                                    <tr>
                                        <td style="padding: 20px;">
                                            <div style="color: #075985; font-size: 15px; line-height: 1.7;">
                                                {!! $reportMessage !!}
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            @if(count($reportRows) > 0)
                                <!-- Report Table -->
                                <table width="100%" cellpadding="0" cellspacing="0" style="border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; margin-bottom: 30px;">
                                    <thead>
                                        <tr style="background: linear-gradient(to right, #667eea, #764ba2);">
                                            <th style="padding: 16px 12px; text-align: left; font-size: 13px; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 0.5px;">
                                                Employee Name
                                            </th>
                                            <th style="padding: 16px 12px; text-align: left; font-size: 13px; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 0.5px;">
                                                Date
                                            </th>
                                            <th style="padding: 16px 12px; text-align: left; font-size: 13px; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 0.5px;">
                                                Check-in
                                            </th>
                                            <th style="padding: 16px 12px; text-align: left; font-size: 13px; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 0.5px;">
                                                Check-out
                                            </th>
                                            <th style="padding: 16px 12px; text-align: left; font-size: 13px; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 0.5px;">
                                                Remarks
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reportRows as $index => $row)
                                            <tr style="background-color: {{ $index % 2 == 0 ? '#ffffff' : '#f9fafb' }};">
                                                <td style="padding: 16px 12px; color: #2d3748; font-size: 14px; font-weight: 600; border-bottom: 1px solid #e2e8f0;">
                                                    {{ $row['name'] }}
                                                </td>
                                                <td style="padding: 16px 12px; color: #4a5568; font-size: 14px; border-bottom: 1px solid #e2e8f0;">
                                                    📅 {{ $row['date'] }}
                                                </td>
                                                <td style="padding: 16px 12px; border-bottom: 1px solid #e2e8f0;">
                                                    @if($row['check_in'])
                                                        <span style="display: inline-block; padding: 6px 12px; background-color: #d1fae5; color: #065f46; border-radius: 6px; font-size: 13px; font-weight: 600;">
                                                            ✓ {{ $row['check_in'] }}
                                                        </span>
                                                    @else
                                                        <span style="display: inline-block; padding: 6px 12px; background-color: #fee2e2; color: #991b1b; border-radius: 6px; font-size: 13px; font-weight: 600;">
                                                            ✗ Not Logged
                                                        </span>
                                                    @endif
                                                </td>
                                                <td style="padding: 16px 12px; border-bottom: 1px solid #e2e8f0;">
                                                    @if($row['check_out'])
                                                        <span style="display: inline-block; padding: 6px 12px; background-color: #dbeafe; color: #1e40af; border-radius: 6px; font-size: 13px; font-weight: 600;">
                                                            ✓ {{ $row['check_out'] }}
                                                        </span>
                                                    @else
                                                        <span style="display: inline-block; padding: 6px 12px; background-color: #fef3c7; color: #92400e; border-radius: 6px; font-size: 13px; font-weight: 600;">
                                                            ⏳ Pending
                                                        </span>
                                                    @endif
                                                </td>
                                                <td style="padding: 16px 12px; color: #4a5568; font-size: 13px; border-bottom: 1px solid #e2e8f0;">
                                                    {{ $row['remarks'] }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <!-- Statistics Summary -->
                                <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f7fafc; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 25px;">
                                    <tr>
                                        <td style="padding: 20px;">
                                            <table width="100%" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td style="padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;">
                                                        <p style="margin: 0; color: #2d3748; font-size: 14px; font-weight: 600;">
                                                            📈 Report Statistics
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-top: 12px;">
                                                        <table width="100%" cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td width="50%" style="padding: 8px 0;">
                                                                    <span style="color: #718096; font-size: 13px;">Total Records:</span>
                                                                    <strong style="color: #2d3748; font-size: 14px; margin-left: 8px;">{{ count($reportRows) }}</strong>
                                                                </td>
                                                                <td width="50%" style="padding: 8px 0;">
                                                                    <span style="color: #718096; font-size: 13px;">Unique Employees:</span>
                                                                    <strong style="color: #2d3748; font-size: 14px; margin-left: 8px;">
                                                                        {{ collect($reportRows)->unique('name')->count() }}
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

                            @else
                                <!-- No Data Message -->
                                <table width="100%" cellpadding="0" cellspacing="0" style="background: linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%); border-radius: 8px; text-align: center; margin-bottom: 30px;">
                                    <tr>
                                        <td style="padding: 50px 30px;">
                                            <div style="font-size: 60px; margin-bottom: 15px;">🎉</div>
                                            <h2 style="color: #22543d; margin: 0 0 10px 0; font-size: 24px; font-weight: 600;">
                                                Perfect Compliance!
                                            </h2>
                                            <p style="color: #276749; margin: 0; font-size: 16px; line-height: 1.6;">
                                                No compliance issues to report this week.<br>
                                                Great job, everyone! 👏
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            <!-- Signature -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 35px; padding-top: 25px; border-top: 2px solid #e2e8f0;">
                                <tr>
                                    <td>
                                        <p style="margin: 0 0 5px 0; color: #4a5568; font-size: 15px;">
                                            Best regards,
                                        </p>
                                        <p style="margin: 0; color: #2d3748; font-size: 16px; font-weight: 600;">
                                            HRMS System
                                        </p>
                                        <p style="margin: 8px 0 0 0; color: #718096; font-size: 13px;">
                                            Automated Attendance Monitoring
                                        </p>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f7fafc; padding: 25px 30px; text-align: center; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0; color: #718096; font-size: 13px; line-height: 1.6;">
                                This is an automated weekly compliance report from the HRMS System.<br>
                                For questions or concerns, please contact the system administrator.
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