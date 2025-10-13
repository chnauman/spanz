<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tender Invitation</title>
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
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 0 0 5px 5px;
        }
        .tender-details {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border-left: 4px solid #007bff;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>New Tender Posted!</h1>
        <p>Hello {{ $user->name }}, a new tender has been posted that matches your interests.</p>
    </div>
    
    <div class="content">
        <div class="tender-details">
            <h2>{{ $tender->title }}</h2>
            <p><strong>Category:</strong> {{ $tender->category->name }}</p>
            <p><strong>Posted by:</strong> {{ $tender->user->name }}</p>
            @if($tender->budget)
            <p><strong>Budget:</strong> {{ $tender->currency }} {{ number_format($tender->budget, 2) }}</p>
            @endif
            <p><strong>Deadline:</strong> {{ $tender->deadline->format('M d, Y') }}</p>
            @if($tender->location)
            <p><strong>Location:</strong> {{ $tender->location }}</p>
            @endif
            
            <h3>Description:</h3>
            <p>{{ $tender->description }}</p>
            
            @if($tender->requirements)
            <h3>Requirements:</h3>
            <p>{{ $tender->requirements }}</p>
            @endif
        </div>
        
        <div style="text-align: center;">
            <a href="{{ route('tenders.detail', $tender->id) }}" class="btn">View Tender Details</a>
        </div>
        
        <div class="footer">
            <p>This email was sent because you have expressed interest in the "{{ $tender->category->name }}" category.</p>
            <p>If you no longer wish to receive these notifications, you can update your interests in your dashboard.</p>
        </div>
    </div>
</body>
</html>
