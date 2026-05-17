<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Welcome to {{ $appName }}</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827; line-height: 1.5;">
    <h1 style="font-size: 22px; margin-bottom: 16px;">Welcome to {{ $appName }}</h1>

    <p>Hello {{ $user->name }},</p>

    <p>Your account has been created successfully. You can now browse products, manage your cart, place orders, and track your purchases from your account dashboard.</p>

    <p style="margin-top: 24px;">Thank you for choosing {{ $appName }}.</p>
</body>
</html>
