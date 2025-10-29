<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Tender Notification</title>
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
        .tender-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #092C48;
        }
        .match-reason {
            background: #e8f4fd;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #2196F3;
        }
        .budget {
            font-size: 18px;
            font-weight: bold;
            color: #092C48;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="font-size: 28px; font-weight: bold; margin: 0 0 10px 0; color: white;">SPANZ</h1>
        <h2 style="margin: 10px 0; font-size: 20px;">New Tender Alert</h2>
    </div>

    <div class="content">
        <h2>Hello {{ $user->name }},</h2>

        <p>A new tender has been posted that matches your interests!</p>

        <div class="match-reason">
            <strong>Why this tender matches your interests:</strong><br>
            {{ $matchReason }}
        </div>

        <div class="tender-details">
            <h3>{{ $tender->title }}</h3>

            <p><strong>Description:</strong><br>
            {{ Str::limit($tender->description, 200) }}</p>

            @if($tender->budget)
            <p><strong>Budget:</strong>
                <span class="budget">{{ number_format($tender->budget, 2) }} {{ $tender->currency ?? 'USD' }}</span>
            </p>
            @endif

            @if($tender->deadline)
            <p><strong>Deadline:</strong> {{ $tender->getFormattedDeadline() }}</p>
            @endif

            @if($tender->location)
            <p><strong>Location:</strong> {{ $tender->location }}</p>
            @endif

            @if($tender->category)
            <p><strong>Category:</strong> {{ $tender->category->name }}</p>
            @endif
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('tenders.detail', $tender->id) }}" class="button">View Tender Details</a>
        </div>

        <p>Don't miss out on this opportunity! Log in to your dashboard to see more details and submit your response.</p>

        <p>Best regards,<br>
        The Spanz Team</p>
    </div>

    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>You can manage your notification preferences in your account settings.</p>
    </div>
</body>
</html>
