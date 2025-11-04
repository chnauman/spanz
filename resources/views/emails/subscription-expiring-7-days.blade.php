@extends('emails.layout')

@section('title', 'Subscription Expiring in 7 Days - Spanz')
@php
    $headerSubtitle = 'Subscription Reminder';
@endphp

@section('content')
<h2>Dear {{ $user->name }},</h2>

<div class="content-box" style="background-color: #ebf8ff; border-left-color: #4299e1;">
    <p style="margin: 0; color: #2c5282;"><strong>Friendly Reminder:</strong> Your {{ $subscription->name }} subscription will expire in 7 days ({{ $expiresAt->format('F j, Y') }}).</p>
</div>

<p>We wanted to give you advance notice so you can renew your subscription and continue enjoying all the benefits of your {{ $subscription->name }} plan without any interruption.</p>

<p><strong>Benefits of renewing early:</strong></p>
<ul>
    <li>Seamless continuation of services</li>
    <li>No downtime or service interruption</li>
    <li>Keep all your saved preferences and data</li>
    <li>Maintain access to premium features</li>
</ul>

<p>Please consider renewing your subscription to avoid any service interruptions. You can renew anytime from your account dashboard.</p>

<p>If you have any questions about your subscription or need assistance, our support team is here to help.</p>

<p style="margin-top: 24px;">Thank you for being a valued member!</p>
@endsection


