<!DOCTYPE html>
<html>
<head>
    <style>
        .container { font-family: Arial, sans-serif; padding: 20px; color: #333; }
        .details { background: #f4f4f4; padding: 15px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login Notification</h2>
        <p>A user has just logged into the system.</p>
        <div class="details">
            <p><strong>Name:</strong> {{ $userName }}</p>
            <p><strong>Email:</strong> {{ $userEmail }}</p>
            <p><strong>Time:</strong> {{ $time }}</p>
            <p><strong>IP:</strong> {{ $ipAddress }}</p>
        </div>
    </div>
</body>
</html>
