<p>Dear {{ $user->name }},</p>

<p>This is a courteous reminder that our records indicate you have not completed the minimum required 8 hours in the
    time tracker for today ({{ \Carbon\Carbon::today()->format('d F Y') }}).</p>

<p>We kindly request that you update your time tracker as soon as possible to ensure accurate and timely reporting.</p>

<p>Thank you for your attention to this matter.</p>

<p>Best regards,<br>HR Team</p>
