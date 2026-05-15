@extends('layouts.admin')

@section('title', 'New Projects / RFXs Received - SPANZ')

@section('content')
<div class="p-6 max-w-5xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">New Projects / RFXs Received</h1>
            <p class="text-gray-600 mt-1">RFXs matching categories in your strengthen profile.</p>
        </div>
        @if($notifications->whereNull('read_at')->count() > 0)
            <form method="POST" action="{{ route('user.rfx-received.read-all') }}">
                @csrf
                <button type="submit" class="btn-secondary btn-secondary-sm">Mark all as read</button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        @forelse($notifications as $notification)
            @php $tender = $notification->tender; @endphp
            <div class="flex items-start justify-between gap-4 px-5 py-4 border-b border-gray-100 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50/50' }}">
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2">
                        @if(!$notification->read_at)
                            <span class="inline-block w-2 h-2 rounded-full bg-blue-600 flex-shrink-0" title="Unread"></span>
                        @endif
                        <h2 class="text-sm font-semibold text-gray-900 truncate">
                            {{ $tender?->cardTitle() ?? 'RFX' }}
                        </h2>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        @if($tender?->category){{ $tender->category->name }} • @endif
                        {{ $notification->created_at->diffForHumans() }}
                    </p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    @if($tender)
                        <a href="{{ route('tenders.detail', ['tender' => $tender->id, 'rfx_notification' => $notification->id]) }}" class="btn-primary btn-primary-sm">View</a>
                    @endif
                    @if(!$notification->read_at)
                        <form method="POST" action="{{ route('user.rfx-received.read', $notification) }}">
                            @csrf
                            <button type="submit" class="text-xs text-gray-600 hover:text-gray-900 underline">Mark read</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-12 text-center text-gray-500">
                <p class="font-medium text-gray-700">No matching RFXs yet</p>
                <p class="text-sm mt-2">Choose categories in your <a href="{{ route('company.register', ['mode' => 'edit']) }}" class="text-blue-600 hover:underline">strengthen profile</a> to receive alerts.</p>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div class="mt-4">{{ $notifications->links() }}</div>
    @endif
</div>
@endsection
