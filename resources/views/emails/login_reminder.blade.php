<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Reminder</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7fa;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f7fa; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); overflow: hidden;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 35px 30px; text-align: center;">
                            <div style="font-size: 50px; margin-bottom: 10px;">⏰</div>
                            <h1 style="color: #ffffff; margin: 0; font-size: 26px; font-weight: 600; letter-spacing: -0.5px;">
                                Attendance Reminder
                            </h1>
                            <p style="color: #ffe0e6; margin: 8px 0 0 0; font-size: 14px;">
                                Login Required
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 35px;">
                            
                            <!-- Greeting -->
                            <p style="margin: 0 0 25px 0; color: #2d3748; font-size: 16px; line-height: 1.6;">
                                Dear <strong>{{ $fullName }}</strong>,
                            </p>

                            <!-- Alert Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff3cd; border-left: 4px solid #ffc107; border-radius: 8px; margin-bottom: 25px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td width="40" style="vertical-align: top;">
                                                    <div style="font-size: 28px;">⚠️</div>
                                                </td>
                                                <td style="padding-left: 15px;">
                                                    <p style="margin: 0 0 8px 0; color: #856404; font-size: 15px; font-weight: 600;">
                                                        Login Not Detected
                                                    </p>
                                                    <p style="margin: 0; color: #856404; font-size: 14px; line-height: 1.6;">
                                                        Our records indicate that you have not logged into the HRMS system today.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Main Message -->
                            <div style="background-color: #f7fafc; border-radius: 8px; padding: 25px; margin-bottom: 25px; border: 1px solid #e2e8f0;">
                                <p style="margin: 0 0 15px 0; color: #2d3748; font-size: 15px; line-height: 1.7;">
                                    Please note that the <strong style="color: #e53e3e;">maximum allowed time to log in is 10:30 AM</strong>, and it is now past that deadline.
                                </p>
                                <p style="margin: 0; color: #2d3748; font-size: 15px; line-height: 1.7;">
                                    We kindly request you to <strong>log in immediately</strong> if you haven't already. Please ensure to avoid late check-ins to stay compliant with our attendance policies.
                                </p>
                            </div>

                            <!-- Important Note -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; margin-bottom: 25px;">
                                <tr>
                                    <td style="padding: 20px; text-align: center;">
                                        <p style="margin: 0 0 8px 0; color: #ffffff; font-size: 14px; font-weight: 600;">
                                            📢 IMPORTANT REMINDER
                                        </p>
                                        <p style="margin: 0; color: #e0e7ff; font-size: 14px; line-height: 1.6;">
                                            Please ensure your availability on <strong style="color: #ffffff;">"Teams"</strong> as per office timing.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Help Section -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f0f9ff; border-radius: 8px; border: 1px solid #bae6fd; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 18px 20px;">
                                        <p style="margin: 0; color: #0c4a6e; font-size: 14px; line-height: 1.6;">
                                            <strong>💡 Need Help?</strong><br>
                                            For any concerns or if you believe this message was sent in error, please feel free to reach out to the HR team.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Closing -->
                            <p style="margin: 0 0 8px 0; color: #2d3748; font-size: 15px; line-height: 1.6;">
                                Thank you for your attention and cooperation.
                            </p>

                            <!-- Signature -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 35px; padding-top: 25px; border-top: 2px solid #e2e8f0;">
                                <tr>
                                    <td>
                                        <p style="margin: 0 0 5px 0; color: #4a5568; font-size: 15px;">
                                            Best regards,
                                        </p>
                                        <p style="margin: 0; color: #2d3748; font-size: 16px; font-weight: 600;">
                                            HR Team
                                        </p>
                                        <p style="margin: 8px 0 0 0; color: #718096; font-size: 13px;">
                                            Human Resources Department
                                        </p>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f7fafc; padding: 25px 30px; text-align: center; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0; color: #718096; font-size: 12px; line-height: 1.6;">
                                This is an automated message from the HRMS Attendance System.<br>
                                Please do not reply to this email.
                            </p>
                            <p style="margin: 12px 0 0 0; color: #a0aec0; font-size: 11px;">
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