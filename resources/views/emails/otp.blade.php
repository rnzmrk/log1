<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #3B82F6; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 8px 8px; }
        .otp { background: #10B981; color: white; font-size: 24px; font-weight: bold; padding: 15px 30px; border-radius: 8px; display: inline-block; letter-spacing: 0.5em; margin: 20px 0; }
        .footer { text-align: center; margin-top: 30px; color: #6b7280; font-size: 14px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>Login Verification</h1>
        </div>
        <div class='content'>
            <h2>Hi there!</h2>
            <p>You're trying to sign in to your account. Please use the verification code below to complete the sign-in process:</p>
            
            <div style='text-align: center;'>
                <div class='otp'>{{ $otp }}</div>
            </div>
            
            <p><strong>This code will expire in 10 minutes.</strong></p>
            <p>If you didn't request this code, please ignore this email.</p>
            
            <div class='footer'>
                <p>This is an automated message. Please do not reply to this email.</p>
            </div>
        </div>
    </div>
</body>
</html>
