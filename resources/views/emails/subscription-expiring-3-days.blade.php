<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subscription Expiring in 3 Days</title>
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
        .notice {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background-color: #007bff;
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
    <div class="header">
        <h1>📅 Subscription Reminder</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $user->name }},</p>
        
        <div class="notice">
            <strong>Friendly Reminder:</strong> Your {{ $subscription->name }} subscription will expire in 3 days ({{ $expiresAt->format('F j, Y') }}).
        </div>
        
        <p>We wanted to give you advance notice so you can renew your subscription and continue enjoying all the benefits of your {{ $subscription->name }} plan without any interruption.</p>
        
        <p><strong>Benefits of renewing early:</strong></p>
        <ul>
            <li>Seamless continuation of services</li>
            <li>No downtime or service interruption</li>
            <li>Keep all your saved preferences and data</li>
            <li>Maintain access to premium features</li>
        </ul>
        
        <p>Renewing is quick and easy - just click the button below to continue your subscription.</p>
        
        <div style="text-align: center;">
            <a href="{{ url('/subscriptions') }}" class="button">Renew Subscription</a>
        </div>
        
        <p>If you have any questions about your subscription or need assistance, our support team is here to help.</p>
        
        <p>Thank you for being a valued member!</p>
    </div>
    
    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>© {{ date('Y') }} Spanz. All rights reserved.</p>
    </div>
</body>
</html>
