@extends('emails.layout')

@section('title', 'Verify Your Email - Spanz')

@section('content')
<h2>Hello{{ $userName ? ' ' . $userName : '' }}!</h2>

<p>Thank you for signing up with Spanz! To complete your registration, please verify your email address using the verification code below.</p>

<div class="button-center">
    <div style="background-color: #f7fafc; padding: 24px; border-radius: 8px; border: 2px dashed #0D6AED; display: inline-block; margin: 20px 0;">
        <div style="font-size: 36px; font-weight: 700; color: #0D6AED; letter-spacing: 8px; font-family: 'Courier New', monospace;">
            {{ $otp }}
        </div>
    </div>
</div>

<p style="color: #718096; font-size: 14px;">This verification code will expire in 15 minutes.</p>

<p>If you did not create an account with Spanz, please ignore this email.</p>

<p style="margin-top: 28px;">Best regards,<br>
<strong style="color: #1a202c;">Spanz Team</strong></p>
@endsection


