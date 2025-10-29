<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Spanz')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .email-wrapper {
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #0D6AED;
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .logo {
            font-size: 42px;
            font-weight: bold;
            color: white;
            margin: 0;
            letter-spacing: 2px;
        }
        .header-subtitle {
            font-size: 18px;
            margin-top: 10px;
            opacity: 0.95;
        }
        .content {
            padding: 30px;
            background-color: #ffffff;
        }
        .button {
            display: inline-block;
            background-color: #0D6AED;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #0a5fc7;
        }
        .button-center {
            text-align: center;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #e9ecef;
        }
        .content-box {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #0D6AED;
        }
        .footer-text {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="header">
            <h1 class="logo">SPANZ</h1>
            @if(isset($headerSubtitle))
            <div class="header-subtitle">{{ $headerSubtitle }}</div>
            @endif
        </div>
        
        <div class="content">
            @yield('content')
        </div>
        
        <div class="footer">
            <p class="footer-text">© {{ date('Y') }} Spanz. All rights reserved.</p>
            <p class="footer-text">This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>

