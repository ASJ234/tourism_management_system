<!DOCTYPE html>
<html>
<head>
    <title>Password Reset Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .panel {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .code {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            letter-spacing: 2px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h2>Password Reset Code</h2>
    
    <p>You have requested to reset your password. Here is your password reset code:</p>
    
    <div class="panel">
        <div class="code">{{ $resetCode }}</div>
    </div>
    
    <p>This code will expire in 15 minutes. If you did not request a password reset, please ignore this email.</p>
    
    <a href="{{ route('password.code') }}" class="button">Reset Password</a>
    
    <p>If you're having trouble clicking the "Reset Password" button, copy and paste this URL into your web browser:</p>
    <p style="word-break: break-all;">{{ route('password.code') }}</p>
    
    <p>Thank you,<br>Tourism Management System</p>
    
    <div class="footer">
        © 2025 Tourism Management System. All Rights Reserved.
    </div>
</body>
</html> 