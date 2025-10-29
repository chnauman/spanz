<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subscription Expired</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .content {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
        }
        .expired {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header" style="background-color: #0D6AED; padding: 20px; text-align: center; border-radius: 8px; margin-bottom: 20px;">
        <h1 style="font-size: 28px; font-weight: bold; margin: 0 0 10px 0; color: white;">SPANZ</h1>
        <h2 style="margin: 10px 0; font-size: 20px; color: white;">❌ Subscription Expired</h2>
    </div>
    
    <div class="content">
        <p>Dear {{ $user->name }},</p>
        
        <div class="expired">
            <strong>Subscription Expired:</strong> Your {{ $subscription->name }} subscription has expired as of {{ $expiresAt->format('F j, Y') }}.
        </div>
        
        <p>We're sorry to inform you that your subscription has now expired. Your account has been downgraded to basic features.</p>
        
        <p><strong>What this means:</strong></p>
        <ul>
            <li>You no longer have access to premium features</li>
            <li>Your account is now limited to basic functionality</li>
            <li>Some of your saved data may be restricted</li>
            <li>You'll need to renew to restore full access</li>
        </ul>
        
        <p>Don't worry - you can reactivate your subscription at any time to restore all your premium features and data.</p>
        
        <div style="text-align: center;">
            <a href="{{ url('/subscriptions') }}" class="button">Reactivate Subscription</a>
        </div>
        
        <p>We miss you already! Renew now to get back all the features you love.</p>
        
        <p>If you have any questions or need assistance, our support team is here to help.</p>
        
        <p>Thank you for being a valued member, and we hope to welcome you back soon!</p>
    </div>
    
    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>© {{ date('Y') }} Spanz. All rights reserved.</p>
    </div>
</body>
</html>
