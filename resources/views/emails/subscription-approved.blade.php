<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subscription Request Approved - SPANZ</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #28a745; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;">
        <h1 style="margin: 0; font-size: 24px;">🎉 Subscription Approved!</h1>
    </div>
    
    <div style="background-color: #f8f9fa; padding: 30px; border-radius: 0 0 8px 8px;">
        <h2 style="color: #092C48; margin-top: 0;">Congratulations!</h2>
        
        <p>Great news! Your subscription request has been approved. You now have access to premium features on SPANZ.</p>
        
        <div style="background-color: white; padding: 20px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #28a745;">
            <h3 style="margin-top: 0; color: #092C48;">Your New Subscription</h3>
            <p><strong>Plan:</strong> {{ $subscriptionRequest->subscription->name }}</p>
            <p><strong>Price:</strong> ${{ number_format($subscriptionRequest->subscription->price, 2) }}/month</p>
            <p><strong>Credits:</strong> {{ $subscriptionRequest->subscription->credits_per_month == -1 ? 'Unlimited' : $subscriptionRequest->subscription->credits_per_month }}</p>
            <p><strong>Status:</strong> <span style="color: #28a745; font-weight: bold;">Active</span></p>
        </div>
        
        <div style="background-color: #e8f5e8; padding: 15px; border-radius: 6px; margin: 20px 0;">
            <h4 style="margin-top: 0; color: #092C48;">What you can do now:</h4>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>View detailed tender information</li>
                <li>Access premium features</li>
                <li>Download project documents</li>
                <li>Contact buyers directly</li>
            </ul>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('tenders.index') }}" 
               style="background-color: #0D6AED; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: bold;">
                Browse Tenders
            </a>
        </div>
        
        <p style="color: #666; font-size: 14px; margin-top: 30px;">
            Thank you for choosing SPANZ! If you have any questions, please don't hesitate to contact our support team.
        </p>
    </div>
</body>
</html>
