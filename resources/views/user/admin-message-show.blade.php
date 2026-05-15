@extends('layouts.admin')

@section('title', $message->subject . ' - SPANZ')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <a href="{{ route('user.messages') }}" class="text-sm text-blue-600 hover:underline mb-4 inline-block">&larr; Back to messages</a>

    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-xl font-bold text-gray-900">{{ $message->subject }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $message->created_at->format('M d, Y g:i A') }}</p>
        <div class="mt-6 text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $message->body }}</div>
    </div>
</div>
@endsection
