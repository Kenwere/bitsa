<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email - Bitsa Club</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #000000 0%, #1a1a2e 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .content {
            padding: 2rem;
        }
        .button {
            display: inline-block;
            background: #00fff5;
            color: #000;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin: 1rem 0;
        }
        .footer {
            background: #f8f9fa;
            padding: 1rem 2rem;
            text-align: center;
            color: #6c757d;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bitsa Club</h1>
            <p>Verify Your Email Address</p>
        </div>
        
        <div class="content">
            <h2>Hello {{ $user->name }},</h2>
            <p>Thank you for registering with Bitsa Club. Please verify your email address by clicking the button below:</p>
            
            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="button">Verify Email Address</a>
            </div>
            
            <p>If you're having trouble clicking the button, copy and paste the following URL into your web browser:</p>
            <p style="word-break: break-all; color: #666; background: #f8f9fa; padding: 10px; border-radius: 5px;">
                {{ $verificationUrl }}
            </p>
            
            <p>This verification link will expire in 24 hours.</p>
            <p>If you didn't create an account, no further action is required.</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Bitsa Club. All rights reserved.</p>
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>