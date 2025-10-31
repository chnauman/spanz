@extends('emails.layout')

@section('title', 'Subscription Not Approved - Spanz')
@php
    $headerSubtitle = 'Subscription Request Update';
@endphp

@section('content')
<h2>Hello {{ $user->name }},</h2>

<div class="content-box" style="background-color: #fff5f5; border-left-color: #e53e3e;">
    <p style="margin: 0; color: #742a2a;">We reviewed your subscription request for the <strong>{{ $subscription->name }}</strong> plan. Unfortunately, it was not approved at this time.</p>
  </div>

@if(!empty($request->admin_notes))
<p><strong>Reason:</strong> {{ $request->admin_notes }}</p>
@endif

<p>You can update your details or choose a different plan and submit again.</p>

<div class="button-center">
    <a href="{{ url('/subscriptions') }}" class="button" style="background-color: #0D6AED; color: #ffffff !important; text-decoration: none;">View Plans</a>
  </div>

<p>If you believe this is a mistake, please reply to this email and our team will assist.</p>
@endsection



