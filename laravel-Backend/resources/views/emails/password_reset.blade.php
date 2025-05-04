<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
</head>
<body>
    <h1>Password Reset Request</h1>
    <p>Hello,</p>
    <p>You requested to reset your password. Click the link below to reset it:</p>
    <a href="{{ $resetLink }}">{{ $resetLink }}</a>
    <p>If you did not request this, please ignore this email.</p>
    <p>Thank you!</p>
</body>
</html>
