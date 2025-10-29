@extends('emails.layout')

@section('title', 'Sub Supplier Invitation - Spanz')
@php
    $headerSubtitle = 'Sub Supplier Invitation';
@endphp

@section('content')
<h2 style="color: #333; margin-top: 0;">Hello {{ $invitee->name }},</h2>

<p>You have received a sub supplier invitation from <strong>{{ $inviter->name }}</strong>.</p>

@if($invitation->message)
<div class="content-box">
    <strong>Message:</strong><br>
    {{ $invitation->message }}
</div>
@endif

<p>By accepting this invitation, you will become a sub supplier of {{ $inviter->name }} and will have access to their tender opportunities.</p>

<div class="button-center">
    <a href="{{ route('invitations.accept', $invitation->id) }}" class="button">Accept Invitation</a>
    <a href="{{ route('invitations.decline', $invitation->id) }}" class="button" style="background-color: #dc3545; margin-left: 10px;">Decline Invitation</a>
</div>

<p>You can also manage your invitations by logging into your dashboard and going to the Invitations section.</p>

<p>Best regards,<br>
<strong>Spanz Team</strong></p>
@endsection
