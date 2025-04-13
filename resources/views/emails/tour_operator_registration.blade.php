<!DOCTYPE html>
<html>
<head>
    <title>Tour Operator Registration</title>
</head>
<body>
    <h2>Welcome to Tourism Management System!</h2>
    
    <p>Dear {{ $name }},</p>
    
    <p>Your registration as a Tour Operator has been successfully completed. You can now log in to your account using the following details:</p>
    
    <p>Email: {{ $email }}</p>
    
    <p>To access your account, please click the following link:</p>
    <p><a href="{{ $login_url }}">Login to Your Account</a></p>
    
    <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>
    
    <p>Best regards,<br>
    Tourism Management System Team</p>
</body>
</html> 