@extends('layouts.admin')
@section('title', 'Invitations - SPANZ')
@section('content')
<div class="bg-gray-100 p-4 sm:p-6 lg:p-8"></div>
    <div class="w-full">
        <div class="border border-gray-300 p-3 sm:p-4 lg:p-6 bg-white rounded-lg shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold">Invitations</h1>
                    <p class="text-gray-300 mt-1">Manage your sub supplier invitations</p>
                </div>
                <a href="{{ route('invite.sub-suppliers') }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Invite Suppliers
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Filter Tabs -->
            <div class="mt-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8">
                        <a href="{{ route('invitations.index', ['type' => 'received', 'filter' => $filter]) }}"
                           class="py-2 px-1 border-b-2 font-medium text-sm {{ $type === 'received' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Received Invitations
                        </a>
                        <a href="{{ route('invitations.index', ['type' => 'sent', 'filter' => $filter]) }}"
                           class="py-2 px-1 border-b-2 font-medium text-sm {{ $type === 'sent' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Sent Invitations
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Status Filters -->
            <div class="mt-6">
                <div class="flex space-x-4">
                    <a href="{{ route('invitations.index', ['type' => $type, 'filter' => 'all']) }}"
                       class="px-3 py-2 text-sm font-medium rounded-md {{ $filter === 'all' ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:text-gray-700' }}">
                        All
                    </a>
                    <a href="{{ route('invitations.index', ['type' => $type, 'filter' => 'pending']) }}"
                       class="px-3 py-2 text-sm font-medium rounded-md {{ $filter === 'pending' ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:text-gray-700' }}">
                        Pending
                    </a>
                    <a href="{{ route('invitations.index', ['type' => $type, 'filter' => 'accepted']) }}"
                       class="px-3 py-2 text-sm font-medium rounded-md {{ $filter === 'accepted' ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:text-gray-700' }}">
                        Accepted
                    </a>
                    <a href="{{ route('invitations.index', ['type' => $type, 'filter' => 'declined']) }}"
                       class="px-3 py-2 text-sm font-medium rounded-md {{ $filter === 'declined' ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:text-gray-700' }}">
                        Declined
                    </a>
                </div>
            </div>

            <!-- Invitations Table -->
            @if($invitations->count() > 0)
                <div class="mt-6">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $type === 'sent' ? 'Invited Supplier' : 'Inviting Supplier' }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Message
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($invitations as $invitation)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                        <span class="text-blue-600 font-medium text-sm">
                                                            {{ strtoupper(substr($type === 'sent' ? $invitation->invitee->name : $invitation->inviter->name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $type === 'sent' ? $invitation->invitee->name : $invitation->inviter->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $type === 'sent' ? $invitation->invitee->email : $invitation->inviter->email }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($invitation->status === 'pending')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            @elseif($invitation->status === 'accepted')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Accepted
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Declined
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $invitation->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            @if($invitation->message)
                                                <div class="max-w-xs truncate" title="{{ $invitation->message }}">
                                                    {{ $invitation->message }}
                                                </div>
                                            @else
                                                <span class="text-gray-400">No message</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if($type === 'received' && $invitation->status === 'pending')
                                                <div class="flex justify-end space-x-2">
                                                    <form method="POST" action="{{ route('invitations.accept', $invitation->id) }}" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                                class="text-green-600 hover:text-green-900 text-sm font-medium">
                                                            Accept
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('invitations.decline', $invitation->id) }}" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                                class="text-red-600 hover:text-red-900 text-sm font-medium">
                                                            Decline
                                                        </button>
                                                    </form>
                                                </div>
                                            @elseif($type === 'sent' && $invitation->status === 'pending')
                                                <form method="POST" action="{{ route('invitations.cancel', $invitation->id) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-red-600 hover:text-red-900 text-sm font-medium"
                                                            onclick="return confirm('Are you sure you want to cancel this invitation?')">
                                                        Cancel
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-400 text-sm">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $invitations->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="mt-6">
                    <div class="text-center py-8">
                        <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.709M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No invitations found</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            @if($type === 'received')
                                You haven't received any invitations yet.
                            @else
                                You haven't sent any invitations yet.
                            @endif
                        </p>
                        @if($type === 'sent')
                            <div class="mt-4">
                                <a href="{{ route('invite.sub-suppliers') }}"
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Invite Suppliers
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
