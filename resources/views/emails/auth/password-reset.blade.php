<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Reset your password</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827; line-height: 1.5;">
    <h1 style="font-size: 22px; margin-bottom: 16px;">Reset your password</h1>

    <p>Hello {{ $user->name ?? 'there' }},</p>

    <p>We received a request to reset the password for your {{ $appName }} account.</p>

    <p style="margin: 28px 0;">
        <a
            href="{{ $resetUrl }}"
            style="display: inline-block; background: #2456C6; color: #ffffff; padding: 12px 18px; border-radius: 8px; text-decoration: none; font-weight: bold;"
        >
            Reset Password
        </a>
    </p>

    <p>If you did not request a password reset, you can safely ignore this email.</p>
</body>
</html>
