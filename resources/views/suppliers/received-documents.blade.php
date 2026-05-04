@extends('layouts.admin')
@section('title', 'Received documents - SPANZ')
@section('content')
<div class="bg-gray-100 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="mx-auto max-w-5xl">
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 bg-[#092C48] px-6 py-5 text-white">
                <h1 class="text-xl font-bold sm:text-2xl">Received documents</h1>
                <p class="mt-1 text-sm text-blue-100">Files shared with you by buyers or other suppliers from the supplier directory.</p>
            </div>

            <div class="p-6">
                @forelse($receipts as $receipt)
                    @php($share = $receipt->share)
                    @php($sender = $share?->sender)
                    @php($senderCompany = $sender?->companyDetail)
                    <article class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-slate-50/60 last:mb-0">
                        <div class="flex flex-col gap-4 border-b border-gray-200 bg-white p-5 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0">
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">From</p>
                                <h2 class="mt-1 text-lg font-bold text-gray-900">{{ $senderCompany?->company_name ?? $sender?->name ?? 'Unknown sender' }}</h2>
                                <p class="text-sm text-gray-600">{{ $sender?->name }}</p>
                                <p class="mt-2 text-sm text-gray-600">
                                    <span class="font-medium text-gray-800">Email:</span>
                                    <a href="mailto:{{ $sender?->email }}" class="text-[#0d6aed] hover:underline">{{ $sender?->email }}</a>
                                </p>
                                @if($senderCompany?->phone && strcasecmp($senderCompany->phone, 'Not provided') !== 0)
                                    <p class="text-sm text-gray-600"><span class="font-medium text-gray-800">Phone:</span> {{ $senderCompany->phone }}</p>
                                @endif
                                @if($senderCompany?->website)
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium text-gray-800">Website:</span>
                                        <a href="{{ $senderCompany->website }}" target="_blank" rel="noopener noreferrer" class="text-[#0d6aed] hover:underline break-all">{{ $senderCompany->website }}</a>
                                    </p>
                                @endif
                                <p class="mt-2 text-xs text-gray-500">Received {{ $receipt->created_at?->format('d M Y, H:i') }}</p>
                            </div>
                            <div class="shrink-0 rounded-lg bg-blue-50 px-4 py-2 text-center text-sm text-[#032747]">
                                <div class="text-2xl font-bold text-[#0d6aed]">{{ $share?->files?->count() ?? 0 }}</div>
                                <div class="text-xs font-medium uppercase tracking-wide text-gray-600">Files</div>
                            </div>
                        </div>

                        @if(filled($share?->message))
                            <div class="border-b border-gray-200 bg-white px-5 py-4">
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Message</p>
                                <p class="mt-1 whitespace-pre-wrap text-sm text-gray-800">{{ $share->message }}</p>
                            </div>
                        @endif

                        <div class="p-5">
                            <h3 class="mb-3 text-sm font-bold uppercase tracking-wide text-gray-700">Downloads</h3>
                            <ul class="space-y-2">
                                @foreach($share?->files ?? [] as $file)
                                    <li class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-gray-200 bg-white px-4 py-3">
                                        <div class="min-w-0 flex items-center gap-3">
                                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-[#0d6aed]" aria-hidden="true">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                                </svg>
                                            </span>
                                            <div class="min-w-0">
                                                <p class="truncate font-semibold text-gray-900">{{ $file->original_name }}</p>
                                                <p class="text-xs text-gray-500">
                                                    @if($file->size)
                                                        {{ number_format($file->size / 1024, 1) }} KB
                                                    @endif
                                                    @if($file->mime)
                                                        <span class="text-gray-400"> · </span>{{ $file->mime }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <a href="{{ route('suppliers.received-documents.download', [$share, $file]) }}"
                                            class="inline-flex shrink-0 items-center gap-2 rounded-md bg-[#0d6aed] px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                                            Download
                                            <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12 12 16.5m-4.5-4.5L12 16.5m0 0 4.5-4.5M12 16.5V3"/>
                                            </svg>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </article>
                @empty
                    <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 py-16 text-center">
                        <p class="text-lg font-semibold text-gray-800">No documents yet</p>
                        <p class="mt-2 text-sm text-gray-600">When a buyer or supplier shares files with you from the directory, they will appear here.</p>
                        <a href="{{ route('suppliers.directory') }}" class="mt-4 inline-block text-sm font-semibold text-[#0d6aed] hover:underline">Browse supplier directory</a>
                    </div>
                @endforelse

                @if($receipts->hasPages())
                    <div class="mt-6 flex justify-center">
                        {{ $receipts->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
