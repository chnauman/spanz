@php($canShareDocuments = $canShareDocuments ?? false)

<div class="text-[#092C48] font-sans mb-5">
    <div class="text-base sm:text-lg text-[#3d5a73] mb-2">
        <span>Displaying </span>
        <span class="font-semibold text-[#092C48]">1 to {{ $suppliers->count() }} </span>
        <span>out of </span>
        <span class="font-semibold text-[#092C48]">{{ $suppliers->total() }} </span>
        <span>suppliers </span>
        @if(request('search'))
            <span class="text-blue-600">for "{{ request('search') }}"</span>
        @endif
    </div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <p class="text-2xl sm:text-3xl font-bold leading-snug tracking-tight text-[#032747]">
            @if(request('search'))
                Search results for "{{ request('search') }}"
            @else
                Supplier directory
            @endif
        </p>
        @if(request('search'))
            <a href="{{ route('suppliers.directory') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-sm text-base font-semibold">
                Clear search
            </a>
        @endif
    </div>
</div>

@if($suppliers->isEmpty())
    <div class="bg-white border border-gray-200 rounded-sm p-4 sm:p-6 font-sans">
        <div class="text-center py-12">
            @if(request('search'))
                <h3 class="text-xl sm:text-2xl font-bold text-[#092C48] mb-2">No suppliers found</h3>
                <p class="text-gray-700 text-base sm:text-lg mb-4">No suppliers matched "{{ request('search') }}". Try different keywords or adjust filters.</p>
                <a href="{{ route('suppliers.directory') }}" class="btn-primary">
                    View all suppliers
                </a>
            @else
                <h3 class="text-xl sm:text-2xl font-bold text-[#092C48] mb-2">No suppliers found</h3>
                <p class="text-gray-700 text-base sm:text-lg">No approved suppliers match your filters yet.</p>
            @endif
        </div>
    </div>
@else
    @foreach($suppliers as $supplier)
        @include('suppliers.partials.directory-result-card', ['supplier' => $supplier, 'canShareDocuments' => $canShareDocuments])
    @endforeach
@endif

@if($suppliers->hasPages())
    <div class="mt-6 flex justify-center">
        {{ $suppliers->links() }}
    </div>
@endif
