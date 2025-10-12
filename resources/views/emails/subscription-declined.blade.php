<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subscription Request Declined - SPANZ</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #dc3545; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;">
        <h1 style="margin: 0; font-size: 24px;">Subscription Request Declined</h1>
    </div>
    
    <div style="background-color: #f8f9fa; padding: 30px; border-radius: 0 0 8px 8px;">
        <h2 style="color: #092C48; margin-top: 0;">Request Not Approved</h2>
        
        <p>We regret to inform you that your subscription request has been declined.</p>
        
        <div style="background-color: white; padding: 20px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #dc3545;">
            <h3 style="margin-top: 0; color: #092C48;">Request Details</h3>
            <p><strong>Plan:</strong> {{ $subscriptionRequest->subscription->name }}</p>
            <p><strong>Price:</strong> ${{ number_format($subscriptionRequest->subscription->price, 2) }}/month</p>
            <p><strong>Requested:</strong> {{ $subscriptionRequest->requested_at->format('M d, Y H:i') }}</p>
            <p><strong>Status:</strong> <span style="color: #dc3545; font-weight: bold;">Declined</span></p>
        </div>
        
        @if($subscriptionRequest->admin_notes)
        <div style="background-color: #fff3cd; padding: 15px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #ffc107;">
            <h4 style="margin-top: 0; color: #856404;">Admin Notes:</h4>
            <p style="color: #856404; margin: 0;">{{ $subscriptionRequest->admin_notes }}</p>
        </div>
        @endif
        
        <div style="background-color: #e8f4fd; padding: 15px; border-radius: 6px; margin: 20px 0;">
            <h4 style="margin-top: 0; color: #092C48;">What you can do:</h4>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>Submit a new subscription request</li>
                <li>Contact our support team for assistance</li>
                <li>Complete your company profile for better approval chances</li>
            </ul>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('subscriptions.index') }}" 
               style="background-color: #0D6AED; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: bold;">
                View Subscription Plans
            </a>
        </div>
        
        <p style="color: #666; font-size: 14px; margin-top: 30px;">
            If you have any questions about this decision, please contact our support team. We're here to help!
        </p>
    </div>
</body>
</html>
