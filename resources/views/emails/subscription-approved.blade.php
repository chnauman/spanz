@extends('emails.layout')

@section('title', 'Subscription Approved - Spanz')
@php
    $headerSubtitle = 'Subscription Request Update';
@endphp

@section('content')
<h2>Great news, {{ $user->name }}!</h2>

<div class="content-box" style="background-color: #ebf8ff; border-left-color: #4299e1;">
    <p style="margin: 0; color: #2c5282;">Your subscription request for the <strong>{{ $subscription->name }}</strong> plan has been approved.</p>
  </div>

<p>You now have supplier access. You can start using all the features included in your plan right away.</p>

@if(!empty($request->admin_notes))
<p><strong>Note from admin:</strong> {{ $request->admin_notes }}</p>
@endif

<div class="button-center">
    <a href="{{ url('/dashboard') }}" class="button" style="background-color: #0D6AED; color: #ffffff !important; text-decoration: none;">Go to Dashboard</a>
  </div>

<p>Welcome aboard!</p>
@endsection



