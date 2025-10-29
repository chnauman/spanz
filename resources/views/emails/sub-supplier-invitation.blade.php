<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sub Supplier Invitation</title>
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
            background: #092C48;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .button {
            display: inline-block;
            background: #092C48;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 5px;
        }
        .button:hover {
            background: #1b3963;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="font-size: 28px; font-weight: bold; margin: 0 0 10px 0; color: white;">SPANZ</h1>
        <h2 style="margin: 10px 0; font-size: 20px;">Sub Supplier Invitation</h2>
    </div>

    <div class="content">
        <h2>Hello {{ $invitee->name }},</h2>

        <p>You have received a sub supplier invitation from <strong>{{ $inviter->name }}</strong>.</p>

        @if($invitation->message)
        <div style="background: white; padding: 15px; border-left: 4px solid #092C48; margin: 20px 0;">
            <strong>Message:</strong><br>
            {{ $invitation->message }}
        </div>
        @endif

        <p>By accepting this invitation, you will become a sub supplier of {{ $inviter->name }} and will have access to their tender opportunities.</p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('invitations.accept', $invitation->id) }}" class="button">Accept Invitation</a>
            <a href="{{ route('invitations.decline', $invitation->id) }}" class="button" style="background: #dc3545;">Decline Invitation</a>
        </div>

        <p>You can also manage your invitations by logging into your dashboard and going to the Invitations section.</p>

        <p>Best regards,<br>
        The Spanz Team</p>
    </div>

    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
    </div>
</body>
</html>
