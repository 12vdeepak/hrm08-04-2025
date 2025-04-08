<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Access Credentials for Quantum IT Innovation HRM Portal</title>
</head>

<body>
    <div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2;">
        <div style="margin:50px auto;width:70%; border-radius:10px;border:1px solid black;">
            <div
                style="background:rgb(42, 97, 215);padding:0px 10px; border-top-left-radius:10px; border-top-right-radius:10px">
                <a href="" style="font-size:1.4em;color: #ffffff;text-decoration:none;font-weight:600;">Quantum IT
                    Innovation HRM </a>
            </div>
            <div style="padding:0px 10px;">
                <p style="font-size:1em;">Dear, {{ $data['name'] }}</p>
                <p style="color:cadetblue;">Welcome to Quantum IT Innovation! We are thrilled to have you as a part of
                    our
                    team. As a new employee, we want to provide you with the necessary credentials to access our HRM
                    (Human
                    Resource Management) portal, which will enable you to conveniently perform tasks such as Check-In
                    and
                    Check-Out.</p>
                <br>
                <br>
                <p style="font-size:1rem;"><b>Below are your login credentials for the Quantum IT Innovation HRM
                        portal:</b>
                </p>
                <ul style="color:slategrey">
                    
                    <li>
                        Reset Password Link: <a href="{{ $data['link'] }}">{{ $data['link'] }}</a>
                    </li>
                    <li>
                        Portal URL: <a href="{{ $data['url'] }}">{{ $data['url'] }}</a>
                    </li>
                    <li>
                        Email: {{ $data['email'] }}
                    </li>
                    <li>
                        Password: {{ $data['password'] }}
                    </li>
                </ul>
                <br>
                <br>

                <p style="font-size:1rem;"><b>Please follow the instructions below to access the HRM portal:</b></p>
                <ol>
                    <li>
                        Open your preferred web browser and visit the Quantum IT Innovation HRM portal using the
                        provided
                        URL.
                    </li>
                    <li>
                        Enter your email mentioned above.
                    </li>
                    <li>
                        Use the temporary password provided to log in to the portal.
                    </li>
                    <li>
                        Upon successful login, you can find Change Password section on sidebar. Choose a strong, secure
                        password of your choice and ensure its confidentiality.
                    </li>
                </ol>
                <br>
                <br>
                <p>Once again, welcome to Quantum IT Innovation! We look forward to working with you and wish you great
                    success in your role.</p>
                <p>Best regards,</p>
                <p>HR Team</p>
                <p>Quantum IT Innovation</p>
            </div>
        </div>
    </div>
</body>
</html>
