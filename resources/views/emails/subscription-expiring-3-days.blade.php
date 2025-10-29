@extends('emails.layout')

@section('title', 'Subscription Expiring in 3 Days - Spanz')
@php
    $headerSubtitle = 'Subscription Reminder';
@endphp

@section('content')
<h2 style="color: #333; margin-top: 0;">Dear {{ $user->name }},</h2>

<div class="content-box" style="background-color: #d1ecf1; border-left-color: #17a2b8;">
    <p style="margin: 0; color: #0c5460;"><strong>Friendly Reminder:</strong> Your {{ $subscription->name }} subscription will expire in 3 days ({{ $expiresAt->format('F j, Y') }}).</p>
</div>

<p>We wanted to give you advance notice so you can renew your subscription and continue enjoying all the benefits of your {{ $subscription->name }} plan without any interruption.</p>

<p><strong>Benefits of renewing early:</strong></p>
<ul>
    <li>Seamless continuation of services</li>
    <li>No downtime or service interruption</li>
    <li>Keep all your saved preferences and data</li>
    <li>Maintain access to premium features</li>
</ul>

<p>Renewing is quick and easy - just click the button below to continue your subscription.</p>

<div class="button-center">
    <a href="{{ url('/subscriptions') }}" class="button">Renew Subscription</a>
</div>

<p>If you have any questions about your subscription or need assistance, our support team is here to help.</p>

<p>Thank you for being a valued member!</p>
@endsection
