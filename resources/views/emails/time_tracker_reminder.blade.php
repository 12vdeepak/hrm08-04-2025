<p>Hi {{ $user->name }},</p>

<p>This is a reminder that you have not logged at least 8 hours in the time tracker for today
    ({{ \Carbon\Carbon::today()->toFormattedDateString() }}).</p>

<p>Please update your time tracker before midnight.</p>

<p>Thank you,<br>HR Team</p>
