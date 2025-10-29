@extends('emails.layout')

@section('title', 'Invitation to Join Spanz')
@php
    $headerSubtitle = 'You\'re Invited to Join SPANZ';
@endphp

@section('content')
<h2 style="color: #333; margin-top: 0;">Hello {{ $invitation->name }},</h2>

<p><strong>{{ $supplier->name }}</strong> has invited you to join SPANZ as a Sub Supplier.</p>

@if($invitation->message)
<div class="content-box">
    <strong>Message from {{ $supplier->name }}:</strong><br>
    {{ $invitation->message }}
</div>
@endif

<p>As a Sub Supplier, you'll have access to:</p>
<ul>
    <li>View and bid on tenders</li>
    <li>Access to supplier network</li>
    <li>Direct communication with buyers</li>
    <li>Subscription management</li>
</ul>

<p>To get started, please complete your registration:</p>

<div class="button-center">
    <a href="{{ route('register', ['token' => $invitation->token]) }}" class="button">Complete Registration</a>
</div>

<p>If you have any questions, please contact {{ $supplier->name }} directly.</p>

<p><small>This invitation expires on {{ $invitation->expires_at->format('M d, Y \a\t g:i A') }}.</small></p>
@endsection
