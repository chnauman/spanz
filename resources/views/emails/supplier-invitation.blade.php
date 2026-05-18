@extends('emails.layout')

@section('title', 'Invitation to Join Spanz')
@php
    $headerSubtitle = 'You\'re Invited to Join SPANZ';
    $companyName = $supplier->companyDetail?->company_name;
@endphp

@section('content')
<h2>Hello {{ $invitation->name }},</h2>

<p><strong>{{ $supplier->name }}</strong> wants you to join SPANZ as a colleague@if($companyName) for <strong>{{ $companyName }}</strong>@endif.</p>

<p>You have received an invitation from <strong>{{ $supplier->name }}</strong> to join the Spanz platform as part of their supplier team.</p>

@if($invitation->message)
<div class="content-box">
    <strong>Message from {{ $supplier->name }}:</strong><br>
    {{ $invitation->message }}
</div>
@endif

<p>As a colleague you will:</p>
<ul>
    <li>Use your company email: <strong>{{ $invitation->email }}</strong></li>
    <li>Share the same company profile as your team</li>
    <li>Share your team's subscription and credits on SPANZ</li>
    <li>View tenders and work alongside {{ $supplier->name }}</li>
</ul>

<p>Click below to accept the invitation and complete your registration:</p>

<div class="button-center">
    <a href="{{ route('supplier.invitation.show', $invitation->token) }}" class="button" style="background-color: #0D6AED; color: #ffffff !important; text-decoration: none;">Accept Invitation</a>
</div>

<p style="color: #718096; font-size: 14px; margin-top: 24px;">This invitation expires on {{ $invitation->expires_at->format('M d, Y \a\t g:i A') }}.</p>

<p style="color: #718096; font-size: 13px;">If you did not expect this invitation, you can safely ignore this email.</p>
@endsection
