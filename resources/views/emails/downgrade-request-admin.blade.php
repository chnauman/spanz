@extends('emails.layout')

@section('title', 'New Downgrade Request - Spanz')
@php
    $headerSubtitle = 'Downgrade Request Notification';
@endphp

@section('content')
<h2>New Downgrade Request</h2>

<div class="content-box" style="background-color: #fffbea; border-left-color: #d69e2e;">
    <p style="margin: 0; color: #744210;">A user has requested a downgrade.</p>
    @if(!empty($request->reason))
        <p style="margin: 8px 0 0 0; color: #744210;"><strong>Reason:</strong> {{ $request->reason }}</p>
    @endif
  </div>

<p><strong>User:</strong> {{ $user->name }} ({{ $user->email }})</p>
<p><strong>Current Plan:</strong> {{ $currentSubscription->name }}</p>
<p><strong>Requested At:</strong> {{ optional($request->requested_at)->format('F j, Y g:i A') }}</p>

<p>Please review this request in the admin panel.</p>
@endsection



