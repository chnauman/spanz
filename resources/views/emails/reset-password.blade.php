@extends('emails.layout')

@section('title', 'Reset Password - Spanz')

@section('content')
<h2 style="color: #333; margin-top: 0;">Hello!</h2>

<p>You are receiving this email because we received a password reset request for your account.</p>

<div class="button-center">
    <a href="{{ $url }}" class="button">Reset Password</a>
</div>

<p>This password reset link will expire in 60 minutes.</p>

<p>If you did not request a password reset, no further action is required.</p>

<p>Best regards,<br>
<strong>Spanz Team</strong></p>
@endsection
