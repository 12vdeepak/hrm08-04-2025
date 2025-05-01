<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            color: #2c3e50;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .message {
            margin-bottom: 20px;
        }
        .dates {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .footer {
            margin-top: 30px;
            color: #7f8c8d;
            font-size: 14px;
        }
        .regards {
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">Happy {{ $festival->occasion }}, {{ $user->name }}!</div>
    
    <div class="message">
        Wishing you and your family a wonderful {{ $festival->occasion }} celebration!
    </div>
    
    <div class="dates">
        @if($festival->start_date == $festival->end_date)
        Our office will be closed on {{ \Carbon\Carbon::parse($festival->start_date)->format('l, F j, Y') }} for this occasion.
        @else
        Our office will be closed from {{ \Carbon\Carbon::parse($festival->start_date)->format('l, F j, Y') }} to {{ \Carbon\Carbon::parse($festival->end_date)->format('l, F j, Y') }} for this occasion.
        @endif
    </div>
    
    <div class="message">
        May this festival bring you joy, prosperity, and happiness!
    </div>
    
    <div class="regards">Warm regards,</div>
    <div class="footer">Quantum IT Innovation</div>
</body>
</html>