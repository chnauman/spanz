<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subscription Expiring Tomorrow</title>
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
        .warning {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background-color: #dc3545;
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
        <h1>🚨 Last Day to Renew</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $user->name }},</p>
        
        <div class="warning">
            <strong>Urgent:</strong> Your {{ $subscription->name }} subscription expires tomorrow ({{ $expiresAt->format('F j, Y') }}).
        </div>
        
        <p>This is your final warning! Your subscription will end tomorrow, and you'll lose access to all premium features.</p>
        
        <p><strong>What you'll lose if you don't renew:</strong></p>
        <ul>
            <li>Access to premium features and tools</li>
            <li>Priority customer support</li>
            <li>Advanced analytics and reporting</li>
            <li>Unlimited usage and storage</li>
        </ul>
        
        <p>Don't wait - renew now to avoid any service interruption. This is your last chance to maintain uninterrupted access.</p>
        
        <div style="text-align: center;">
            <a href="{{ url('/subscriptions') }}" class="button">Renew Now - Last Chance!</a>
        </div>
        
        <p>If you're experiencing any issues with renewal, please contact our support team immediately.</p>
        
        <p>We value your business and don't want to see you go!</p>
    </div>
    
    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>© {{ date('Y') }} Spanz. All rights reserved.</p>
    </div>
</body>
</html>
