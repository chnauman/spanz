@extends('emails.layout')

@section('title', 'Reset Password - Spanz')

@section('content')
<h2>Hello!</h2>

<p>You are receiving this email because we received a password reset request for your account.</p>

<div class="button-center">
    <a href="{{ $url }}" class="button" style="background-color: #0D6AED; color: #ffffff !important; text-decoration: none;">Reset Password</a>
</div>

<p style="color: #718096; font-size: 14px;">This password reset link will expire in 60 minutes.</p>

<p>If you did not request a password reset, no further action is required. Your password will remain unchanged.</p>

<p style="margin-top: 28px;">Best regards,<br>
<strong style="color: #1a202c;">Spanz Team</strong></p>
@endsection
