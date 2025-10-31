@extends('emails.layout')

@section('title', 'New Upgrade Request - Spanz')
@php
    $headerSubtitle = 'Upgrade Request Notification';
@endphp

@section('content')
<h2>New Upgrade Request</h2>

<div class="content-box" style="background-color: #fffbea; border-left-color: #d69e2e;">
    <p style="margin: 0; color: #744210;">A user has requested an upgrade.</p>
</div>

<p><strong>User:</strong> {{ $user->name }} ({{ $user->email }})</p>
<p><strong>Requested Plan:</strong> {{ $subscription->name }}</p>
<p><strong>Requested At:</strong> {{ optional($request->requested_at)->format('F j, Y g:i A') }}</p>

<p>Please review this request in the admin panel.</p>

<div class="button-center">
    <a href="{{ route('admin.subscription-requests') }}" class="button" style="background-color: #0D6AED; color: #ffffff !important; text-decoration: none; padding: 12px 24px; border-radius: 4px; display: inline-block;">View Request</a>
</div>
@endsection



