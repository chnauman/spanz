@php
    $cd = $supplier->companyDetail;
    $locationParts = array_filter([
        $cd->city ?? null,
        $cd->state ?? null,
        $cd->country ?? null,
    ], fn ($v) => $v !== null && $v !== '' && strcasecmp((string) $v, 'Not provided') !== 0);
    $locationLine = $locationParts !== [] ? implode(', ', $locationParts) : ($cd->headquarter_location ?: 'Location not specified');
    $interestCategories = $supplier->interests->map(fn ($i) => $i->category)->filter()->unique('id');
@endphp

<div class="thomas-result-card font-sans p-5 sm:p-6 {{ !$loop->first ? 'mt-5' : '' }}">
    <div class="supplier-card-head flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between lg:gap-6">
        <div class="supplier-card-head-main flex min-w-0 flex-1 gap-3">
            @if($canShareDocuments && auth()->check() && auth()->id() !== (int) $supplier->id)
                <div class="shrink-0 pt-1">
                    <label class="sr-only">Select supplier for document share</label>
                    <input type="checkbox" class="supplier-select-checkbox mt-1 h-5 w-5 rounded border-gray-300 text-[#0d6aed] focus:ring-[#0d6aed]"
                        data-user-id="{{ $supplier->id }}" title="Select (max 3) to share documents">
                </div>
            @endif
            <div class="supplier-card-title-stack min-w-0 flex-1">
                @if($supplier->isSubSupplier())
                    <span class="mb-2 inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold uppercase tracking-wide sm:text-sm" style="background-color:#f3e8ff;color:#6b21a8;">
                        <span class="h-2 w-2 shrink-0 rounded-full" style="background-color:#6b21a8;" aria-hidden="true"></span>
                        Sub-supplier
                    </span>
                @else
                    <span class="mb-2 inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold uppercase tracking-wide sm:text-sm" style="background-color:#e8f2ff;color:#0d6aed;">
                        <span class="h-2 w-2 shrink-0 rounded-full" style="background-color:#0d6aed;" aria-hidden="true"></span>
                        Supplier
                    </span>
                @endif
                <p class="block text-xl font-bold leading-snug tracking-tight text-gray-900 sm:text-2xl break-words">
                    {{ $cd->company_name ?? $supplier->name }}
                </p>
                <p class="mt-1 text-sm text-gray-600">{{ $supplier->name }}</p>
                @if($supplier->isSubSupplier() && $supplier->parentSupplier?->companyDetail?->company_name)
                    <p class="mt-1 text-sm text-gray-500">Network: {{ $supplier->parentSupplier->companyDetail->company_name }}</p>
                @endif
            </div>
        </div>
        <div class="flex flex-wrap items-center justify-start gap-2 lg:justify-end lg:shrink-0">
            @if($cd->website)
                <a href="{{ $cd->website }}" target="_blank" rel="noopener noreferrer" class="thomas-primary-btn">
                    Website
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            @endif
        </div>
    </div>

    <div class="mt-5 border-t border-gray-100 pt-5">
        <div class="grid grid-cols-1 sm:grid-cols-3">
            <div class="flex gap-3 border-b border-gray-100 py-3 sm:border-b-0 sm:border-r sm:py-0 sm:pr-6">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-[#0d6aed]" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">Contact</div>
                    <div class="mt-0.5 text-sm font-bold leading-snug text-gray-900 sm:text-[15px] break-all">{{ $supplier->email }}</div>
                </div>
            </div>
            <div class="flex gap-3 border-b border-gray-100 py-3 sm:border-b-0 sm:border-r sm:px-6 sm:py-0">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-[#0d6aed]" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">Phone</div>
                    <div class="mt-0.5 text-sm font-bold leading-snug text-gray-900 sm:text-[15px]">
                        {{ $cd->phone && strcasecmp($cd->phone, 'Not provided') !== 0 ? $cd->phone : '—' }}
                    </div>
                </div>
            </div>
            <div class="flex gap-3 py-3 sm:py-0 sm:pl-6">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-[#0d6aed]" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">Employees</div>
                    <div class="mt-0.5 text-sm font-bold leading-snug text-gray-900 sm:text-[15px]">{{ $cd->employees_range ?: '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 flex flex-col gap-3 border-t border-gray-100 pt-4 lg:flex-row lg:items-center lg:justify-between lg:gap-4">
        <div class="flex min-w-0 flex-1 gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-[#0d6aed]" aria-hidden="true">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <div class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">About</div>
                <p class="mt-0.5 text-sm leading-snug text-gray-900 line-clamp-3 sm:text-[15px]">{{ $cd->description ?: '—' }}</p>
            </div>
        </div>
        <div class="flex shrink-0 lg:self-center">
            <span class="inline-flex items-center gap-1.5 rounded-full border border-green-200 bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-800 sm:text-sm">
                <svg class="h-4 w-4 shrink-0 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                {{ $locationLine }}
            </span>
        </div>
    </div>

    @if($interestCategories->isNotEmpty())
        <div class="mt-4 rounded-xl border border-gray-200 bg-slate-50/80 p-4 sm:p-5">
            <div class="mb-3 flex gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white text-[#0d6aed] shadow-sm ring-1 ring-gray-100" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div class="min-w-0 py-0.5">
                    <h3 class="text-base font-bold leading-snug text-gray-900 sm:text-lg">Categories (registration interests)</h3>
                    <p class="mt-0.5 text-xs text-gray-500 sm:text-sm">Used to match this supplier with relevant opportunities</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                {!! $interestCategories->map(fn ($c) => '<span class="inline-flex rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-semibold text-gray-800 sm:text-sm">'.e($c->name).'</span>')->implode('') !!}
            </div>
        </div>
    @endif
</div>
