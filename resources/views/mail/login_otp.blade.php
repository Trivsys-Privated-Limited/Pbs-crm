<!DOCTYPE html>
<html>
<head>
    <style>
        .container { font-family: Arial, sans-serif; padding: 20px; color: #333; }
        .otp-code { font-size: 24px; font-weight: bold; color: #4A90E2; letter-spacing: 5px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login Security Verification</h2>
        <p>Your One-Time Password (OTP) for CRM login is:</p>
        <div class="otp-code">{{ $otp }}</div>
        <p>This code will expire in 15 minutes.</p>
    </div>
</body>
</html>
