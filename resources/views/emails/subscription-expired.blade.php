@extends('emails.layout')

@section('title', 'Subscription Expired - Spanz')
@php
    $headerSubtitle = 'Subscription Expired';
@endphp

@section('content')
<h2 style="color: #333; margin-top: 0;">Dear {{ $user->name }},</h2>

<div class="content-box" style="background-color: #f8d7da; border-left-color: #dc3545;">
    <p style="margin: 0; color: #721c24;"><strong>Subscription Expired:</strong> Your {{ $subscription->name }} subscription has expired as of {{ $expiresAt->format('F j, Y') }}.</p>
</div>

<p>We're sorry to inform you that your subscription has now expired. Your account has been downgraded to basic features.</p>

<p><strong>What this means:</strong></p>
<ul>
    <li>You no longer have access to premium features</li>
    <li>Your account is now limited to basic functionality</li>
    <li>Some of your saved data may be restricted</li>
    <li>You'll need to renew to restore full access</li>
</ul>

<p>Don't worry - you can reactivate your subscription at any time to restore all your premium features and data.</p>

<div class="button-center">
    <a href="{{ url('/subscriptions') }}" class="button">Reactivate Subscription</a>
</div>

<p>We miss you already! Renew now to get back all the features you love.</p>

<p>If you have any questions or need assistance, our support team is here to help.</p>

<p>Thank you for being a valued member, and we hope to welcome you back soon!</p>
@endsection
