@extends('emails.layout')

@section('title', 'New Tender Posted - Spanz')
@php
    $headerSubtitle = 'New Tender Posted!';
@endphp

@section('content')
<h2>Hello {{ $user->name }},</h2>

<p>A new tender has been posted that matches your interests.</p>

<div class="content-box">
    <h2 style="margin-top: 0;">{{ $tender->title }}</h2>
    <p><strong>Category:</strong> {{ $tender->category->name }}</p>
    @if($tender->budget)
    <p><strong>Budget:</strong> {{ $tender->currency }} {{ number_format($tender->budget, 2) }}</p>
    @endif
    <p><strong>Deadline:</strong> {{ $tender->deadline->format('M d, Y') }}</p>
    @if($tender->location)
    <p><strong>Location:</strong> {{ $tender->location }}</p>
    @endif
    
    <h3>Description:</h3>
    <p>{{ $tender->description }}</p>
    
    @if($tender->requirements)
    <h3>Requirements:</h3>
    <p>{{ $tender->requirements }}</p>
    @endif
</div>

<div class="button-center">
    <a href="{{ route('tenders.detail', $tender->id) }}" class="button" style="background-color: #0D6AED; color: #ffffff !important; text-decoration: none;">View Tender Details</a>
</div>

<p>This email was sent because you have expressed interest in the "{{ $tender->category->name }}" category.</p>
<p>If you no longer wish to receive these notifications, you can update your interests in your dashboard.</p>
@endsection
