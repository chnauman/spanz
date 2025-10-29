<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Spanz')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f5f7fa;
            padding: 20px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .email-wrapper {
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07), 0 1px 3px rgba(0, 0, 0, 0.06);
        }
        .header {
            background: linear-gradient(135deg, #0D6AED 0%, #0a5fc7 100%);
            color: #ffffff;
            padding: 40px 30px;
            text-align: center;
        }
        .logo {
            font-size: 48px;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
            letter-spacing: 3px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header-subtitle {
            font-size: 20px;
            margin-top: 12px;
            opacity: 0.95;
            font-weight: 400;
        }
        .content {
            padding: 40px 35px;
            background-color: #ffffff;
        }
        .content h2 {
            color: #1a202c;
            font-size: 24px;
            font-weight: 600;
            margin: 0 0 20px 0;
            line-height: 1.3;
        }
        .content p {
            color: #4a5568;
            font-size: 16px;
            margin: 0 0 16px 0;
            line-height: 1.6;
        }
        .content ul {
            color: #4a5568;
            font-size: 16px;
            margin: 16px 0;
            padding-left: 24px;
        }
        .content li {
            margin: 8px 0;
            line-height: 1.6;
        }
        .button {
            display: inline-block;
            background-color: #0D6AED;
            color: #ffffff !important;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 6px;
            margin: 24px 0;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            border: none;
            box-shadow: 0 2px 4px rgba(13, 106, 237, 0.2);
            transition: all 0.3s ease;
        }
        .button:hover {
            background-color: #0a5fc7;
            box-shadow: 0 4px 8px rgba(13, 106, 237, 0.3);
            transform: translateY(-1px);
        }
        .button-center {
            text-align: center;
            margin: 28px 0;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 24px 30px;
            text-align: center;
            font-size: 13px;
            color: #718096;
            border-top: 1px solid #e2e8f0;
        }
        .footer-text {
            margin: 6px 0;
            line-height: 1.5;
        }
        .content-box {
            background-color: #f7fafc;
            padding: 24px;
            border-radius: 8px;
            margin: 24px 0;
            border-left: 4px solid #0D6AED;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        .content-box h2,
        .content-box h3 {
            color: #0D6AED;
            margin-top: 0;
            margin-bottom: 12px;
        }
        .content-box p {
            color: #4a5568;
            margin-bottom: 12px;
        }
        .content-box strong {
            color: #2d3748;
            font-weight: 600;
        }
        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }
            .header {
                padding: 30px 20px;
            }
            .logo {
                font-size: 36px;
            }
            .header-subtitle {
                font-size: 18px;
            }
            .content {
                padding: 30px 25px;
            }
            .button {
                padding: 12px 24px;
                font-size: 15px;
                display: block;
                width: 100%;
                margin: 20px 0;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
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
    </div>
</body>
</html>
