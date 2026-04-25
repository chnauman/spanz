<div class="text-[#092C48] mb-5">
    <div class="text-sm sm:text-base mb-2">
        <span>Displaying </span>
        <span class="font-semibold">1 to {{ $tenders->count() }} </span>
        <span>out of </span>
        <span class="font-semibold">{{ $tenders->total() }} </span>
        <span>tenders </span>
        @if(request('search'))
            <span class="text-blue-600">for "{{ request('search') }}"</span>
        @endif
    </div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <p class="text-3xl font-semibold leading-tight tracking-tight">
            @if(request('search'))
                Search Results for "{{ request('search') }}"
            @else
                Featured Tenders and Opportunities
            @endif
        </p>
        @if(request('search'))
            <a href="{{ route('tenders.search') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-sm text-sm">
                Clear Search
            </a>
        @endif
    </div>
</div>

@forelse($tenders as $tender)
    <div class="thomas-result-card p-4 sm:p-5 {{ !$loop->first ? 'mt-5' : '' }}">
        @php($typeKey = is_string($tender->request_type) ? strtolower($tender->request_type) : null)
        @php($typeLabel = in_array($typeKey, ['rfq','rft','rfp','eoi'], true) ? strtoupper($typeKey) : null)
        <div class="flex items-start justify-between gap-4 mb-2">
            <div class="min-w-0 flex-1">
                <a href="{{ route('tenders.detail', $tender->id) }}" class="block text-[#0f3351] font-semibold text-[2rem] leading-none hover:text-blue-600 truncate">{{ $tender->title }}</a>
                <p class="text-sm text-[#1f3f5f] mt-1">{{ $tender->user->name ?? 'Tender Owner' }}</p>
            </div>
            <div class="flex items-center gap-4 shrink-0">
                @auth
                    <button type="button" class="thomas-action-link" onclick="toggleSave({{ $tender->id }})" id="save-btn-{{ $tender->id }}">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M7 5.75h10a.75.75 0 0 1 .75.75v12.2a.3.3 0 0 1-.46.25L12 15.4l-5.29 3.55a.3.3 0 0 1-.46-.25V6.5A.75.75 0 0 1 7 5.75z" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                        <span id="save-text-{{ $tender->id }}">Save</span>
                    </button>
                @else
                    <button type="button" class="thomas-action-link disabled" disabled id="save-btn-{{ $tender->id }}">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M7 5.75h10a.75.75 0 0 1 .75.75v12.2a.3.3 0 0 1-.46.25L12 15.4l-5.29 3.55a.3.3 0 0 1-.46-.25V6.5A.75.75 0 0 1 7 5.75z" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                        <span id="save-text-{{ $tender->id }}">Save</span>
                    </button>
                @endauth
                <button type="button" class="thomas-action-link">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="12" cy="12" r="8.25" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M12 8.25v7.5M8.25 12h7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <span>Select</span>
                </button>
                <a href="{{ route('tenders.detail', $tender->id) }}" class="thomas-primary-btn inline-flex items-center gap-2">
                    View Details
                </a>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-gray-600 mb-2">
            @if($typeLabel)
                <span class="font-semibold text-[#092C48]">{{ $typeLabel }}</span>
                <span class="text-gray-300">|</span>
            @endif
            <span>
                <span class="font-medium">Budget:</span>
                {{ $tender->budget ? ($tender->currency ? $tender->currency . ' ' : '') . number_format($tender->budget, 0) : '—' }}
            </span>
            <span class="text-gray-300">|</span>
            <span>
                <span class="font-medium">Posted:</span>
                {{ optional($tender->created_at)->format('d M, Y') ?? '—' }}
            </span>
            @if($tender->deadline)
                <span class="text-gray-300">|</span>
                <span>
                    <span class="font-medium">Deadline:</span>
                    {{ is_string($tender->deadline) ? \Carbon\Carbon::parse($tender->deadline)->format('d M, Y') : $tender->deadline->format('d M, Y') }}
                </span>
            @endif
        </div>
        <div class="flex items-center gap-2 my-2">
            <img src="{{ asset('spanz-img/location.svg') }}" alt="Location" class="w-4 sm:w-5">
            <span class="text-[#092C48] text-base">{{ $tender->location ?? 'Location not specified' }}</span>
        </div>
        <div class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1 lg:w-[75%]">
                <div class="flex items-center mb-2 gap-2">
                    <img src="{{ asset('spanz-img/factory.svg') }}" alt="Category" class="w-4 sm:w-5">
                    <span class="text-[#092C48] font-semibold text-xs sm:text-sm lg:text-base">
                        {{ $tender->category->name }} . {{ $tender->budget ? ($tender->currency ? $tender->currency . ' ' : '') . number_format($tender->budget, 0) : 'Budget not specified' }}
                    </span>
                </div>
                <div>
                    <p class="text-[#092C48] text-[1.07rem] leading-relaxed">
                        {{ Str::limit($tender->description, 200) }}
                    </p>
                </div>

                @php($subCategoryLabels = [
                    'electrical' => 'Electrical',
                    'mechanical' => 'Mechanical',
                    'engines' => 'Engines',
                    'avionics' => 'Avionics',
                    'apus' => 'Auxiliary Power Units (APUs)',
                    'navigation' => 'Navigation systems',
                    'communication' => 'Communication systems (radio, satellite)',
                ])
                @php($rows = is_array($tender->categories) ? $tender->categories : [])
                @php($breakdown = collect($rows)->map(function ($row) use ($subCategoryLabels) {
                    if (!is_array($row)) return null;
                    $rawLabel = $row['sub_category'] ?? $row['work'] ?? $row['type'] ?? null;
                    $label = null;
                    if (is_string($rawLabel) && $rawLabel !== '') {
                        $key = strtolower($rawLabel);
                        $label = $subCategoryLabels[$key] ?? ucwords(str_replace(['_', '-'], ' ', $rawLabel));
                    }

                    $pctRaw = $row['product_type'] ?? $row['percentage'] ?? $row['percent'] ?? null;
                    $pct = null;
                    if (is_numeric($pctRaw)) {
                        $pctNum = (int) $pctRaw;
                        $pct = $pctNum === 5 ? '<10%' : ($pctNum . '%');
                    } elseif (is_string($pctRaw) && trim($pctRaw) !== '') {
                        $pct = trim($pctRaw);
                    }

                    if (!$label && !$pct) return null;
                    return ['label' => $label ?: 'Work', 'pct' => $pct ?: '—'];
                })->filter()->values())

                @if($breakdown->isNotEmpty())
                    <div class="mt-3">
                        <div class="text-sm font-semibold text-[#092C48]">Indicative Budget Break Down:</div>
                        <div class="mt-2 flex flex-wrap gap-2 text-xs sm:text-sm text-gray-700">
                            @foreach($breakdown as $item)
                                <span class="thomas-chip whitespace-nowrap">
                                    {{ $item['label'] }} – {{ $item['pct'] }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@empty
    <div class="bg-white border border-gray-200 rounded-sm p-4 sm:p-6">
        <div class="text-center py-12">
            @if(request('search'))
                <h3 class="text-lg font-semibold text-[#092C48] mb-2">No Tenders Found</h3>
                <p class="text-gray-600 mb-4">No tenders found for "{{ request('search') }}". Try different keywords or browse all tenders.</p>
                <a href="{{ route('tenders.search') }}" class="bg-[#0D6AED] hover:bg-blue-700 text-white px-4 py-2 rounded-sm text-sm">
                    View All Tenders
                </a>
            @else
                <h3 class="text-lg font-semibold text-[#092C48] mb-2">No Tenders Found</h3>
                <p class="text-gray-600">There are currently no active tenders available.</p>
            @endif
        </div>
    </div>
@endforelse

@if($tenders->hasPages())
    <div class="mt-6 flex justify-center">
        {{ $tenders->links() }}
    </div>
@endif

