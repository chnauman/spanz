<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Supplier Invitation</title>
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
            background-color: #0D6AED;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .button {
            display: inline-block;
            background-color: #0D6AED;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>You're Invited to Join SPANZ</h1>
    </div>

    <div class="content">
        <h2>Hello {{ $subSupplier->name }},</h2>

        <p><strong>{{ $supplier->name }}</strong> has invited you to join SPANZ as a Sub Supplier.</p>

        @if($message)
        <div style="background-color: #e8f4fd; padding: 15px; border-left: 4px solid #0D6AED; margin: 20px 0;">
            <strong>Message from {{ $supplier->name }}:</strong><br>
            {{ $message }}
        </div>
        @endif

        <p>As a Sub Supplier, you'll have access to:</p>
        <ul>
            <li>View and bid on tenders</li>
            <li>Access to supplier network</li>
            <li>Direct communication with buyers</li>
            <li>Subscription management</li>
        </ul>

        <p>To get started, please complete your registration:</p>

        <a href="{{ route('register') }}" class="button">Complete Registration</a>

        <p>If you have any questions, please contact {{ $supplier->name }} directly.</p>
    </div>

    <div class="footer">
        <p>This invitation was sent by {{ $supplier->name }} through SPANZ.</p>
        <p>If you did not expect this invitation, you can safely ignore this email.</p>
    </div>
</body>
</html>
