<div class="text-[#092C48] font-sans mb-5">
    <div class="text-base sm:text-lg text-[#3d5a73] mb-2">
        <span>Displaying </span>
        <span class="font-semibold text-[#092C48]">1 to {{ $tenders->count() }} </span>
        <span>out of </span>
        <span class="font-semibold text-[#092C48]">{{ $tenders->total() }} </span>
        <span>tenders </span>
        @if(request('search'))
            <span class="text-blue-600">for "{{ request('search') }}"</span>
        @endif
    </div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <p class="text-2xl sm:text-3xl font-bold leading-snug tracking-tight text-[#032747]">
            @if(request('search'))
                Search Results for "{{ request('search') }}"
            @else
                Featured Tenders and Opportunities
            @endif
        </p>
        @if(request('search'))
            <a href="{{ route('tenders.search') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-sm text-base font-semibold">
                Clear Search
            </a>
        @endif
    </div>
</div>

@forelse($tenders as $tender)
    @php
        $typeKey = is_string($tender->request_type) ? strtolower($tender->request_type) : null;
        $typeLabel = in_array($typeKey, ['rfq', 'rft', 'rfp', 'eoi'], true) ? strtoupper($typeKey) : null;
        $categoryGroups = $tender->categoriesGroupedForDisplay();
        $allocationCards = collect();
        foreach ($categoryGroups as $group) {
            foreach ($group['lines'] as $line) {
                $pctNum = \App\Models\Tender::parsePctDisplayToNumber($line['pct']);
                $allocationCards->push([
                    'main_name' => $group['main_name'],
                    'sub_label' => $line['sub_label'],
                    'pct' => $line['pct'],
                    'pct_num' => $pctNum,
                    'range' => $tender->budget ? $tender->formatAllocatedBudgetRange($pctNum) : null,
                ]);
            }
        }
    @endphp

    <div class="thomas-result-card font-sans p-5 sm:p-6 {{ !$loop->first ? 'mt-5' : '' }}">
        {{-- Header: type badge + title (left), actions (right), aligned on large screens --}}
        <div class="tender-card-head flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between lg:gap-6">
            <div class="min-w-0 flex-1">
                @if($typeLabel)
                    {{-- Request-type pill: pale blue field, blue dot, saturated blue label (inline colors = reliable) --}}
                    <span class="mb-2 inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold uppercase tracking-wide sm:text-sm" style="background-color:#e8f2ff;color:#0d6aed;">
                        <span class="h-2 w-2 shrink-0 rounded-full" style="background-color:#0d6aed;" aria-hidden="true"></span>
                        {{ $typeLabel }}
                    </span>
                @endif
                <a href="{{ route('tenders.detail', $tender->id) }}" class="block text-xl font-bold leading-snug tracking-tight text-gray-900 hover:text-[#0d6aed] sm:text-2xl break-words">{{ $tender->titleHeadline() }}</a>
            </div>
            <div class="tender-card-head-actions flex flex-wrap items-center justify-start gap-2 lg:justify-end lg:shrink-0">
                @auth
                    <button type="button" class="thomas-outline-btn" onclick="toggleSave({{ $tender->id }})" id="save-btn-{{ $tender->id }}">
                        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M7 5.75h10a.75.75 0 0 1 .75.75v12.2a.3.3 0 0 1-.46.25L12 15.4l-5.29 3.55a.3.3 0 0 1-.46-.25V6.5A.75.75 0 0 1 7 5.75z" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                        <span id="save-text-{{ $tender->id }}">Save</span>
                    </button>
                @else
                    <button type="button" class="thomas-outline-btn disabled opacity-50 cursor-not-allowed" disabled id="save-btn-{{ $tender->id }}">
                        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M7 5.75h10a.75.75 0 0 1 .75.75v12.2a.3.3 0 0 1-.46.25L12 15.4l-5.29 3.55a.3.3 0 0 1-.46-.25V6.5A.75.75 0 0 1 7 5.75z" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                        <span id="save-text-{{ $tender->id }}">Save</span>
                    </button>
                @endauth
                <button type="button" class="thomas-outline-btn">
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="12" cy="12" r="8.25" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M12 8.25v7.5M8.25 12h7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    Select
                </button>
                <a href="{{ route('tenders.detail', $tender->id) }}" class="thomas-primary-btn">
                    View Details
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- Budget / Posted / Deadline: 3 columns + faint vertical rules (desktop) --}}
        <div class="mt-5 border-t border-gray-100 pt-5">
            <div class="tender-card-meta-grid grid grid-cols-1 sm:grid-cols-3">
                <div class="tender-meta-cell flex gap-3 border-b border-gray-100 py-3 sm:border-b-0 sm:border-r sm:py-0 sm:pr-6">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-[#0d6aed]" aria-hidden="true">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <div class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">Budget</div>
                        <div class="mt-0.5 text-sm font-bold leading-snug text-gray-900 sm:text-[15px]">{{ $tender->budget ? $tender->budgetRangeLabel() : '—' }}</div>
                    </div>
                </div>
                <div class="tender-meta-cell flex gap-3 border-b border-gray-100 py-3 sm:border-b-0 sm:border-r sm:px-6 sm:py-0">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-[#0d6aed]" aria-hidden="true">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <div class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">Posted</div>
                        <div class="mt-0.5 text-sm font-bold leading-snug text-gray-900 sm:text-[15px]">{{ optional($tender->created_at)->format('d M, Y') ?? '—' }}</div>
                    </div>
                </div>
                <div class="tender-meta-cell flex gap-3 py-3 sm:py-0 sm:pl-6">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-[#0d6aed]" aria-hidden="true">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <div class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">Deadline</div>
                        <div class="mt-0.5 text-sm font-bold leading-snug text-gray-900 sm:text-[15px]">
                            @if($tender->deadline)
                                {{ is_string($tender->deadline) ? \Carbon\Carbon::parse($tender->deadline)->format('d M, Y') : $tender->deadline->format('d M, Y') }}
                            @else
                                —
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Project description + location --}}
        <div class="tender-desc-row mt-4 border-t border-gray-100 pt-4">
            <div class="tender-desc-row-main flex min-w-0 flex-1 gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-[#0d6aed]" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="tender-desc-text-col min-w-0">
                    <div class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">Project description</div>
                    <p class="mt-0.5 text-sm leading-snug text-gray-900 line-clamp-2 sm:text-[15px]">{{ $tender->description }}</p>
                </div>
            </div>
            <div class="tender-desc-row-badge flex shrink-0">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-green-200 bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-800 sm:text-sm" style="border-color:#bbf7d0;background-color:#f0fdf4;color:#166534;">
                    <svg class="h-4 w-4 shrink-0 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $tender->displayLocation() }}
                </span>
            </div>
        </div>

        @if(filled($tender->product_or_service))
            <div class="mt-4 flex flex-col gap-3 border-t border-gray-100 pt-4">
                <div class="tender-desc-row-main flex min-w-0 flex-1 gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-[#0d6aed]" aria-hidden="true">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <div class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">Product or service required</div>
                        <p class="mt-0.5 text-sm leading-snug text-gray-900 line-clamp-2 sm:text-[15px]">{{ $tender->product_or_service }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Categories panel: three allocation cards in one row from sm breakpoint --}}
        @if($allocationCards->isNotEmpty())
            @php($themes = [
                ['txt' => 'text-blue-700', 'bar' => 'bg-blue-500', 'pctColor' => '#1d4ed8', 'barColor' => '#3b82f6'],
                ['txt' => 'text-orange-700', 'bar' => 'bg-yellow-500', 'pctColor' => '#c2410c', 'barColor' => '#eab308'],
                ['txt' => 'text-purple-600', 'bar' => 'bg-purple-600', 'pctColor' => '#9333ea', 'barColor' => '#9333ea'],
            ])
            <div class="mt-4 rounded-xl border border-gray-200 bg-slate-50/80 p-4 sm:p-5">
                <div class="mb-4 flex gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white text-[#0d6aed] shadow-sm ring-1 ring-gray-100" aria-hidden="true">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="min-w-0 py-0.5">
                        <h3 class="text-base font-bold leading-snug text-gray-900 sm:text-lg">Categories &amp; budget allocation</h3>
                        <p class="mt-0.5 text-xs text-gray-500 sm:text-sm">Breakdown of budget by category</p>
                    </div>
                </div>
                <div class="tender-allocation-grid grid grid-cols-1 gap-3 sm:grid-cols-3">
                    @foreach($allocationCards as $card)
                        @php($ti = $loop->index % 3)
                        @php($th = $themes[$ti])
                        @php($barW = min(100, max(0, (int) round($card['pct_num']))))
                        <div class="flex flex-col rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0 pt-0.5">
                                    <div class="text-sm font-bold leading-snug text-gray-900 sm:text-[15px]">{{ $card['main_name'] }}</div>
                                    <div class="mt-0.5 text-[11px] text-gray-500">{{ $card['sub_label'] }}</div>
                                </div>
                                <div class="shrink-0 pt-0.5 text-lg font-bold leading-none sm:text-xl {{ $th['txt'] }}" style="color: {{ $th['pctColor'] }}">{{ $card['pct'] }}</div>
                            </div>
                            <div class="mt-3 h-1.5 w-full overflow-hidden rounded-full bg-gray-100">
                                <div class="{{ $th['bar'] }} h-full rounded-full" style="width: {{ $barW }}%; background-color: {{ $th['barColor'] }}"></div>
                            </div>
                            @if($card['range'])
                                <div class="mt-2 text-[11px] leading-snug text-gray-600">{{ $card['range'] }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @elseif($tender->category)
            <div class="mt-4 rounded-xl border border-gray-200 bg-slate-50/80 p-4">
                <span class="text-sm font-semibold text-gray-900">{{ $tender->category->name }}</span>
            </div>
        @endif
    </div>
@empty
    <div class="bg-white border border-gray-200 rounded-sm p-4 sm:p-6 font-sans">
        <div class="text-center py-12">
            @if(request('search'))
                <h3 class="text-xl sm:text-2xl font-bold text-[#092C48] mb-2">No Tenders Found</h3>
                <p class="text-gray-700 text-base sm:text-lg mb-4">No tenders found for "{{ request('search') }}". Try different keywords or browse all tenders.</p>
                <a href="{{ route('tenders.search') }}" class="bg-[#0D6AED] hover:bg-blue-700 text-white px-4 py-2 rounded-sm text-base font-semibold">
                    View All Tenders
                </a>
            @else
                <h3 class="text-xl sm:text-2xl font-bold text-[#092C48] mb-2">No Tenders Found</h3>
                <p class="text-gray-700 text-base sm:text-lg">There are currently no active tenders available.</p>
            @endif
        </div>
    </div>
@endforelse

@if($tenders->hasPages())
    <div class="mt-6 flex justify-center">
        {{ $tenders->links() }}
    </div>
@endif

