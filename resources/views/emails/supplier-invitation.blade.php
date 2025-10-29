@extends('emails.layout')

@section('title', 'Invitation to Join Spanz')
@php
    $headerSubtitle = 'You\'re Invited to Join SPANZ';
@endphp

@section('content')
<h2>Hello {{ $invitation->name }},</h2>

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
    <a href="{{ route('register', ['token' => $invitation->token]) }}" class="button" style="background-color: #0D6AED; color: #ffffff !important; text-decoration: none;">Complete Registration</a>
</div>

<p>If you have any questions, please contact {{ $supplier->name }} directly.</p>

<p style="color: #718096; font-size: 14px; margin-top: 24px;">This invitation expires on {{ $invitation->expires_at->format('M d, Y \a\t g:i A') }}.</p>
@endsection
