@extends('layouts.admin')

@section('title', 'My Messages - SPANZ')

@section('content')
<div class="p-6 max-w-5xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Messages</h1>
            <p class="text-gray-600 mt-1">Messages from SPANZ administration.</p>
        </div>
        @if($messages->whereNull('read_at')->count() > 0)
            <form method="POST" action="{{ route('user.messages.read-all') }}">
                @csrf
                <button type="submit" class="btn-secondary btn-secondary-sm">Mark all as read</button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden divide-y divide-gray-100">
        @forelse($messages as $message)
            <a href="{{ route('user.messages.show', $message) }}"
               class="block px-5 py-4 hover:bg-gray-50 {{ $message->read_at ? '' : 'bg-blue-50/50' }}">
                <div class="flex items-center gap-2">
                    @if(!$message->read_at)
                        <span class="inline-block w-2 h-2 rounded-full bg-blue-600 flex-shrink-0"></span>
                    @endif
                    <span class="font-semibold text-gray-900">{{ $message->subject }}</span>
                </div>
                <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ Str::limit(strip_tags($message->body), 120) }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $message->created_at->diffForHumans() }}</p>
            </a>
        @empty
            <div class="p-12 text-center text-gray-500">
                <p class="font-medium text-gray-700">No messages yet</p>
                <p class="text-sm mt-2">When an administrator sends you a message, it will appear here.</p>
            </div>
        @endforelse
    </div>

    @if($messages->hasPages())
        <div class="mt-4">{{ $messages->links() }}</div>
    @endif
</div>
@endsection
