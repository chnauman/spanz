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
        <p class="text-xl sm:text-2xl lg:text-3xl xl:text-4xl font-semibold leading-tight">
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
    <div class="bg-white border border-gray-200 rounded-sm p-4 sm:p-6 {{ !$loop->first ? 'mt-5' : '' }}">
        @php($typeKey = is_string($tender->request_type) ? strtolower($tender->request_type) : null)
        @php($typeLabel = in_array($typeKey, ['rfq','rft','rfp','eoi'], true) ? strtoupper($typeKey) : null)
        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs sm:text-sm text-gray-600 mb-2">
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
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
            <a href="{{ route('tenders.detail', $tender->id) }}" class="text-[#092C48] font-semibold text-lg sm:text-xl hover:text-blue-600">{{ $tender->title }}</a>
            <div class="flex gap-4 sm:gap-6">
                @auth
                    <div class="flex items-center gap-2 cursor-pointer" onclick="toggleSave({{ $tender->id }})" id="save-btn-{{ $tender->id }}">
                        <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M6.75 6L7.5 5.25H16.5L17.25 6V19.3162L12 16.2051L6.75 19.3162V6ZM8.25 6.75V16.6838L12 14.4615L15.75 16.6838V6.75H8.25Z"
                                fill="#080341" />
                        </svg>
                        <p class="text-[#092C48] text-sm sm:text-lg" id="save-text-{{ $tender->id }}">Save</p>
                    </div>
                @endauth
            </div>
        </div>
        <div class="flex items-center gap-2 my-3">
            <img src="{{ asset('spanz-img/location.svg') }}" alt="Location" class="w-4 sm:w-5">
            <span class="text-[#092C48] font-semibold text-sm sm:text-base">{{ $tender->location ?? 'Location not specified' }}</span>
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
                    <p class="text-[#092C48] text-sm sm:text-base leading-relaxed">
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
                        <div class="text-xs sm:text-sm font-semibold text-[#092C48]">Indicative Budget Break Down:</div>
                        <div class="mt-1 flex flex-wrap gap-x-4 gap-y-1 text-xs sm:text-sm text-gray-700">
                            @foreach($breakdown as $item)
                                <span class="whitespace-nowrap">
                                    {{ $item['label'] }} – {{ $item['pct'] }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="flex justify-end items-end mt-4">
            <a href="{{ route('tenders.detail', $tender->id) }}"
                class="bg-[#0D6AED] hover:bg-blue-700 px-6 py-3 text-white rounded-sm text-base font-medium">
                View Details
            </a>
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

