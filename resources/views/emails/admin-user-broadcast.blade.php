@extends('emails.layout')

@section('title', $emailSubject . ' - SPANZ')
@php
    $headerSubtitle = 'Message from SPANZ';
@endphp

@section('content')
<h2>Hello{{ $recipientName !== '' ? ', ' . $recipientName : '' }}</h2>

<div class="content-box" style="border-left-color: #0D6AED;">
    <div style="color: #4a5568; font-size: 16px; line-height: 1.7;">{!! nl2br(e($bodyText)) !!}</div>
</div>

<p style="color: #718096; font-size: 14px;">If you have questions, please contact support through your SPANZ account or the website.</p>
@endsection
