@php
    $cd = $supplier->companyDetail;
    $locationParts = array_filter([
        $cd->city ?? null,
        $cd->state ?? null,
        $cd->country ?? null,
    ], fn ($v) => $v !== null && $v !== '' && strcasecmp((string) $v, 'Not provided') !== 0);
    $locationLine = $locationParts !== [] ? implode(', ', $locationParts) : ($cd->headquarter_location ?: 'Location not specified');
    $interestCategories = $supplier->interests->map(fn ($i) => $i->category)->filter()->unique('id');
    $showSharePicker = $canShareDocuments && auth()->check() && auth()->id() !== (int) $supplier->id;
@endphp

<div class="thomas-result-card font-sans overflow-hidden {{ !$loop->first ? 'mt-5' : '' }}">
    <div class="border-b border-slate-100 bg-gradient-to-br from-slate-50/90 via-white to-white px-4 py-5 sm:px-6 sm:py-6">
        {{-- Top bar: small share checkbox beside badge (left); compact Website (right) --}}
        <div class="flex items-center justify-between gap-3">
            <div class="flex min-w-0 flex-1 items-center gap-2">
                @if($showSharePicker)
                    <label class="inline-flex shrink-0 cursor-pointer items-center rounded p-0.5 hover:bg-slate-100/80"
                        aria-label="Select this supplier for document sharing (up to 3)">
                        <input type="checkbox"
                            class="supplier-select-checkbox h-4 w-4 shrink-0 rounded border-slate-300 text-[#0d6aed] focus:ring-[#0d6aed] disabled:cursor-not-allowed disabled:opacity-40"
                            data-user-id="{{ $supplier->id }}">
                    </label>
                @endif
                <div class="flex min-w-0 flex-wrap items-center gap-2">
                    @if($supplier->isSubSupplier())
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-violet-100 px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wide text-violet-800">
                            <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-violet-600" aria-hidden="true"></span>
                            Sub-supplier
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wide text-[#0b5fd7]">
                            <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-[#0d6aed]" aria-hidden="true"></span>
                            Supplier
                        </span>
                    @endif
                </div>
            </div>
            @if($cd->website)
                <a href="{{ $cd->website }}" target="_blank" rel="noopener noreferrer"
                    class="inline-flex shrink-0 items-center gap-1 rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-[#0d6aed] shadow-sm transition hover:border-[#0d6aed]/40 hover:bg-blue-50/80">
                    <span>Website</span>
                    <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            @endif
        </div>

        <h2 class="mt-3 text-xl font-bold leading-tight tracking-tight text-slate-900 sm:text-2xl break-words">
            {{ $cd->company_name ?? $supplier->name }}
        </h2>
        <p class="mt-1 text-sm font-medium text-slate-600">{{ $supplier->name }}</p>
        @if($supplier->isSubSupplier() && $supplier->parentSupplier?->companyDetail?->company_name)
            <p class="mt-1 text-xs text-slate-500 sm:text-sm">Network: <span class="font-semibold text-slate-600">{{ $supplier->parentSupplier->companyDetail->company_name }}</span></p>
        @endif
    </div>

    <div class="px-4 py-4 sm:px-6 sm:py-5">
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4 lg:gap-0 lg:divide-x lg:divide-slate-100">
            <div class="flex min-w-0 gap-3 lg:pr-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-[#0d6aed]" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Email</div>
                    <div class="mt-0.5 text-sm font-semibold leading-snug text-slate-900 break-all">{{ $supplier->email }}</div>
                </div>
            </div>
            <div class="flex min-w-0 gap-3 lg:px-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-[#0d6aed]" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Phone</div>
                    <div class="mt-0.5 text-sm font-semibold leading-snug text-slate-700">
                        @if($cd->phone && strcasecmp($cd->phone, 'Not provided') !== 0)
                            {{ $cd->phone }}
                        @else
                            <span class="font-normal text-slate-400">Not listed</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex min-w-0 gap-3 lg:px-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-[#0d6aed]" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Employees</div>
                    <div class="mt-0.5 text-sm font-semibold leading-snug text-slate-700">
                        @if($cd->employees_range)
                            {{ $cd->employees_range }}
                        @else
                            <span class="font-normal text-slate-400">Not listed</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex min-w-0 items-start gap-3 sm:col-span-2 lg:col-span-1 lg:pl-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Location</div>
                    <div class="mt-0.5 text-sm font-semibold leading-snug text-slate-800">{{ $locationLine }}</div>
                </div>
            </div>
        </div>

        <div class="mt-5 rounded-xl border border-slate-100 bg-slate-50/50 p-4">
            <div class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">About</div>
            @if($cd->description)
                <p class="mt-1.5 text-sm leading-relaxed text-slate-800 line-clamp-4 sm:text-[15px]">{{ $cd->description }}</p>
            @else
                <p class="mt-1.5 text-sm italic text-slate-400">No company description provided.</p>
            @endif
        </div>
    </div>

    @if($interestCategories->isNotEmpty())
        <div class="border-t border-slate-100 bg-slate-50/40 px-4 py-4 sm:px-6 sm:py-5">
            <div class="mb-3 flex items-center gap-2">
                <svg class="h-5 w-5 shrink-0 text-[#0d6aed]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <h3 class="text-sm font-bold text-slate-900 sm:text-base">Registration interests</h3>
            </div>
            <div class="flex flex-wrap gap-2">
                {!! $interestCategories->map(fn ($c) => '<span class="inline-flex rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-xs font-semibold text-slate-700 shadow-sm sm:text-sm">'.e($c->name).'</span>')->implode('') !!}
            </div>
        </div>
    @endif
</div>
