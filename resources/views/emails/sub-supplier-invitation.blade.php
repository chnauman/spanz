@extends('emails.layout')

@section('title', 'Colleague Invitation - Spanz')
@php
    $headerSubtitle = 'Colleague Invitation';
@endphp

@section('content')
<h2>Hello {{ $invitee->name }},</h2>

<p>You have received a colleague invitation from <strong>{{ $inviter->name }}</strong>.</p>

@if($invitation->message)
<div class="content-box">
    <strong>Message:</strong><br>
    {{ $invitation->message }}
</div>
@endif

<p>By accepting this invitation, you will become a colleague of {{ $inviter->name }} and will have access to their tender opportunities.</p>

<div class="button-center">
    <a href="{{ route('invitations.accept', $invitation->id) }}" class="button" style="background-color: #0D6AED; color: #ffffff !important; text-decoration: none; margin-right: 10px;">Accept Invitation</a>
    <a href="{{ route('invitations.decline', $invitation->id) }}" class="button" style="background-color: #e53e3e; color: #ffffff !important; text-decoration: none;">Decline Invitation</a>
</div>

<p>You can also manage your invitations by logging into your dashboard and going to the Invitations section.</p>

<p style="margin-top: 24px;">Best regards,<br>
<strong>Spanz Team</strong></p>
@endsection
