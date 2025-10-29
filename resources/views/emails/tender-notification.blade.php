@extends('emails.layout')

@section('title', 'New Tender Alert - Spanz')
@php
    $headerSubtitle = 'New Tender Alert';
@endphp

@section('content')
<h2 style="color: #333; margin-top: 0;">Hello {{ $user->name }},</h2>

<p>A new tender has been posted that matches your interests!</p>

<div class="content-box" style="background-color: #e8f4fd; border-left-color: #2196F3;">
    <strong>Why this tender matches your interests:</strong><br>
    {{ $matchReason }}
</div>

<div class="content-box">
    <h3 style="margin-top: 0; color: #0D6AED;">{{ $tender->title }}</h3>

    <p><strong>Description:</strong><br>
    {{ Str::limit($tender->description, 200) }}</p>

    @if($tender->budget)
    <p><strong>Budget:</strong>
        <span style="font-size: 18px; font-weight: bold; color: #0D6AED;">{{ number_format($tender->budget, 2) }} {{ $tender->currency ?? 'USD' }}</span>
    </p>
    @endif

    @if($tender->deadline)
    <p><strong>Deadline:</strong> {{ $tender->getFormattedDeadline() }}</p>
    @endif

    @if($tender->location)
    <p><strong>Location:</strong> {{ $tender->location }}</p>
    @endif

    @if($tender->category)
    <p><strong>Category:</strong> {{ $tender->category->name }}</p>
    @endif
</div>

<div class="button-center">
    <a href="{{ route('tenders.detail', $tender->id) }}" class="button">View Tender Details</a>
</div>

<p>Don't miss out on this opportunity! Log in to your dashboard to see more details and submit your response.</p>

<p>Best regards,<br>
<strong>Spanz Team</strong></p>
@endsection
