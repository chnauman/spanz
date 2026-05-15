@extends('emails.layout')

@section('title', 'New Tender Posted - Spanz')
@php
    $headerSubtitle = 'New Tender Posted!';
@endphp

@section('content')
<h2>Hello {{ $user->name }},</h2>

<p>A new RFX has been posted that matches categories in your strengthen profile.</p>

<div class="content-box">
    <h2 style="margin-top: 0;">{{ $tender->cardTitle() }}</h2>
    <p><strong>Category:</strong> {{ $tender->category->name }}</p>
    @if($tender->budget)
    <p><strong>Budget:</strong> {{ $tender->currency }} {{ number_format($tender->budget, 2) }}</p>
    @endif
    <p><strong>Deadline:</strong> {{ $tender->deadline->format('M d, Y') }}</p>
    @if($tender->displayLocation() !== 'Location not specified')
    <p><strong>Location:</strong> {{ $tender->displayLocation() }}</p>
    @endif
    
    <h3>Description:</h3>
    <p>{{ $tender->description }}</p>
    
    @if($tender->requirements)
    <h3>Comments:</h3>
    <p>{{ $tender->requirements }}</p>
    @endif
</div>

<div class="button-center">
    <a href="{{ route('tenders.detail', $tender->id) }}" class="button" style="background-color: #0D6AED; color: #ffffff !important; text-decoration: none;">View Tender Details</a>
</div>

<p>This email was sent because your strengthen profile includes the "{{ $tender->category->name }}" category.</p>
<p>To change which categories you receive alerts for, update your <a href="{{ route('company.register', ['mode' => 'edit']) }}">company profile</a>.</p>
@endsection
