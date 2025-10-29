@extends('emails.layout')

@section('title', 'Subscription Expiring Tomorrow - Spanz')
@php
    $headerSubtitle = 'Last Day to Renew';
@endphp

@section('content')
<h2 style="color: #333; margin-top: 0;">Dear {{ $user->name }},</h2>

<div class="content-box" style="background-color: #fff3cd; border-left-color: #ffc107;">
    <p style="margin: 0; color: #856404;"><strong>Urgent:</strong> Your {{ $subscription->name }} subscription expires tomorrow ({{ $expiresAt->format('F j, Y') }}).</p>
</div>

<p>This is your final warning! Your subscription will end tomorrow, and you'll lose access to all premium features.</p>

<p><strong>What you'll lose if you don't renew:</strong></p>
<ul>
    <li>Access to premium features and tools</li>
    <li>Priority customer support</li>
    <li>Advanced analytics and reporting</li>
    <li>Unlimited usage and storage</li>
</ul>

<p>Don't wait - renew now to avoid any service interruption. This is your last chance to maintain uninterrupted access.</p>

<div class="button-center">
    <a href="{{ url('/subscriptions') }}" class="button">Renew Now - Last Chance!</a>
</div>

<p>If you're experiencing any issues with renewal, please contact our support team immediately.</p>

<p>We value your business and don't want to see you go!</p>
@endsection
