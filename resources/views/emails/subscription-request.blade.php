<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Subscription Request - SPANZ</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #092C48; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;">
        <h1 style="margin: 0; font-size: 24px;">New Subscription Request</h1>
    </div>
    
    <div style="background-color: #f8f9fa; padding: 30px; border-radius: 0 0 8px 8px;">
        <h2 style="color: #092C48; margin-top: 0;">Subscription Request Details</h2>
        
        <p>A new subscription request has been submitted:</p>
        
        <div style="background-color: white; padding: 20px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #0D6AED;">
            <h3 style="margin-top: 0; color: #092C48;">User Information</h3>
            <p><strong>Name:</strong> {{ $subscriptionRequest->user->name }}</p>
            <p><strong>Email:</strong> {{ $subscriptionRequest->user->email }}</p>
            <p><strong>Company:</strong> {{ $subscriptionRequest->user->companyDetail->company_name ?? 'Not provided' }}</p>
        </div>
        
        <div style="background-color: white; padding: 20px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #28a745;">
            <h3 style="margin-top: 0; color: #092C48;">Subscription Details</h3>
            <p><strong>Plan:</strong> {{ $subscriptionRequest->subscription->name }}</p>
            <p><strong>Price:</strong> ${{ number_format($subscriptionRequest->subscription->price, 2) }}/month</p>
            <p><strong>Credits:</strong> {{ $subscriptionRequest->subscription->credits_per_month == -1 ? 'Unlimited' : $subscriptionRequest->subscription->credits_per_month }}</p>
            <p><strong>Requested:</strong> {{ $subscriptionRequest->requested_at->format('M d, Y H:i') }}</p>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('admin.subscription-requests') }}" 
               style="background-color: #0D6AED; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: bold;">
                Review Subscription Request
            </a>
        </div>
        
        <div style="background-color: #e8f4fd; padding: 15px; border-radius: 6px; margin: 20px 0;">
            <h4 style="margin-top: 0; color: #092C48;">Quick Actions:</h4>
            <p style="margin: 5px 0; color: #092C48;"><strong>User:</strong> {{ $subscriptionRequest->user->name }} ({{ $subscriptionRequest->user->email }})</p>
            <p style="margin: 5px 0; color: #092C48;"><strong>Requested Plan:</strong> {{ $subscriptionRequest->subscription->name }} - ${{ number_format($subscriptionRequest->subscription->price, 2) }}/month</p>
            <p style="margin: 5px 0; color: #092C48;"><strong>Action Required:</strong> Please review and approve or decline this subscription request.</p>
        </div>
        
        <p style="color: #666; font-size: 14px; margin-top: 30px;">
            This email was sent because a user requested a subscription upgrade. Please review and approve or decline the request in the admin panel.
        </p>
    </div>
</body>
</html>
