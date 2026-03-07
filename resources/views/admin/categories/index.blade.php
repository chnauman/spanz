@extends('layouts.admin')
@section('title', 'Categories Management - SPANZ')
@section('content')
<div class="bg-gray-100 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="w-full">
        <div class="border border-gray-300 p-3 sm:p-4 lg:p-6 bg-white rounded-lg shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                <h1 class="text-xl sm:text-2xl font-bold">Categories Management</h1>
            </div>

            <!-- Add New Category Button -->
            <div class="mt-4 sm:mt-6">
                <a href="{{ route('admin.categories.create') }}" 
                   class="inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 bg-[#0D6AED] text-white text-sm sm:text-base font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add New Category
                </a>
            </div>

            <!-- Content Container -->
            <div class="mt-6">


        <!-- Categories Table -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <h3 class="text-lg font-medium text-gray-900">All Categories</h3>
                @if($categories->isNotEmpty())
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" id="btn-expand-all" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0D6AED]">
                        <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                        Expand All
                    </button>
                    <button type="button" id="btn-collapse-all" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0D6AED]">
                        <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                        Collapse All
                    </button>
                </div>
                @endif
            </div>
            
            @if($categories->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full table-fixed divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-5/12">
                                Category
                            </th>
                            <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/12">
                                Parent Category
                            </th>
                            <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/12">
                                Status
                            </th>
                            <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/12">
                                Created
                            </th>
                            <th scope="col" class="relative px-4 sm:px-6 py-3 w-1/12">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($categories as $category)
                        @php
                            $isParent = is_null($category->parent_category_id);
                        @endphp
                        <tr class="hover:bg-gray-50 category-row {{ $isParent ? 'category-parent' : 'category-child' }}"
                            data-category-id="{{ $category->id }}"
                            @if(!$isParent) data-parent-id="{{ $category->parent_category_id }}" @endif>
                            <td class="px-4 sm:px-6 py-4 {{ $isParent ? '' : 'pl-10 sm:pl-12' }}">
                                <div class="flex items-center">
                                    @if($isParent)
                                    <button type="button" class="category-toggle flex-shrink-0 mr-2 p-1 rounded hover:bg-gray-200 text-gray-600 focus:outline-none focus:ring-2 focus:ring-[#0D6AED]" data-parent-id="{{ $category->id }}" title="Expand/Collapse children" aria-label="Toggle children">
                                        <svg class="category-toggle-icon h-5 w-5 transition-transform" data-expanded="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    @else
                                    <span class="flex-shrink-0 w-7 inline-block" aria-hidden="true"></span>
                                    @endif
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full {{ $isParent ? 'bg-[#0D6AED]' : 'bg-gray-400' }} flex items-center justify-center">
                                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4 min-w-0 flex-1">
                                        <div class="text-sm font-medium text-gray-900 truncate" title="{{ $category->name }}">{{ $category->name }}</div>
                                        @if($category->description)
                                        <div class="text-sm text-gray-500 truncate" title="{{ $category->description }}">{{ $category->description }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($category->parent)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $category->parent->name }}
                                    </span>
                                @else
                                    <span class="text-gray-400">Main Category</span>
                                @endif
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                @if($category->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $category->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.categories.show', $category) }}" 
                                       class="text-blue-600 hover:text-blue-900 p-2 rounded-md hover:bg-blue-50 transition-colors" 
                                       title="View">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.categories.edit', $category) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 p-2 rounded-md hover:bg-indigo-50 transition-colors" 
                                       title="Edit">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline delete-category-form" 
                                          id="delete-form-{{ $category->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="delete_inactive_tenders" value="0" id="delete-inactive-{{ $category->id }}">
                                        <button type="button" 
                                                data-delete-check-url="{{ route('admin.categories.delete-check', $category) }}"
                                                onclick="confirmDeleteCategory({{ $category->id }}, {{ json_encode($category->name) }}, this)"
                                                class="text-red-600 hover:text-red-900 p-2 rounded-md hover:bg-red-50 transition-colors" 
                                                title="Delete">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No categories</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new category.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.categories.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#0D6AED] hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Category
                    </a>
                </div>
            </div>
            @endif
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
async function confirmDeleteCategory(categoryId, categoryName, buttonEl) {
    const checkUrl = buttonEl?.dataset?.deleteCheckUrl;
    if (!checkUrl) {
        Swal.fire({
            title: 'Error!',
            text: 'Delete check URL is missing. Please refresh and try again.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        return;
    }

    let check;
    try {
        const resp = await fetch(checkUrl, {
            method: 'GET',
            headers: { 'Accept': 'application/json' },
            credentials: 'same-origin',
        });
        if (!resp.ok) throw new Error('Failed to check category usage');
        check = await resp.json();
    } catch (e) {
        Swal.fire({
            title: 'Error!',
            text: 'Could not verify tender status for this category. Please try again.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        return;
    }

    const active = Number(check.active_tenders || 0);
    const inactive = Number(check.inactive_tenders || 0);

    // If any active tender exists (or mix), do not allow delete.
    if (active > 0) {
        const extra = inactive > 0 ? ' (There are also inactive tenders.)' : '';
        Swal.fire({
            title: 'Cannot delete',
            text: 'Category "' + categoryName + '" has ' + active + ' active tender(s)' + extra + ' Remove/close active tenders first.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        return;
    }

    // If only inactive tenders exist, ask if user wants to delete those too.
    if (inactive > 0) {
        const result = await Swal.fire({
            title: 'Tender(s) found',
            text: 'Category "' + categoryName + '" has ' + inactive + ' inactive tender(s). If you continue, the category and these tenders will be deleted.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete category & tenders',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            focusCancel: true
        });

        if (!result.isConfirmed) return;

        document.getElementById('delete-inactive-' + categoryId).value = '1';
    } else {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: 'You are about to delete category "' + categoryName + '". This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            focusCancel: true
        });
        if (!result.isConfirmed) return;
    }

    Swal.fire({
        title: 'Deleting...',
        text: 'Please wait while we delete the category.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const form = document.getElementById('delete-form-' + categoryId);
    form.submit();
}

@if(session('success'))
Swal.fire({
    title: 'Success!',
    text: '{{ session('success') }}',
    icon: 'success',
    confirmButtonText: 'OK'
});
@endif

@if(session('error'))
Swal.fire({
    title: 'Error!',
    text: '{{ session('error') }}',
    icon: 'error',
    confirmButtonText: 'OK'
});
@endif

(function() {
    function getChildRows() { return document.querySelectorAll('.category-row.category-child[data-parent-id]'); }
    function setChildrenVisible(parentId, visible) {
        document.querySelectorAll('.category-row.category-child[data-parent-id="' + parentId + '"]').forEach(function(row) {
            row.style.display = visible ? '' : 'none';
        });
    }
    function setToggleIcon(btn, expanded) {
        if (!btn) return;
        var icon = btn.querySelector('.category-toggle-icon');
        if (!icon) return;
        icon.setAttribute('data-expanded', expanded ? 'true' : 'false');
        icon.style.transform = expanded ? 'rotate(0deg)' : 'rotate(-90deg)';
    }
    document.getElementById('btn-expand-all') && document.getElementById('btn-expand-all').addEventListener('click', function() {
        getChildRows().forEach(function(row) { row.style.display = ''; });
        document.querySelectorAll('.category-toggle').forEach(function(btn) {
            setToggleIcon(btn, true);
        });
    });
    document.getElementById('btn-collapse-all') && document.getElementById('btn-collapse-all').addEventListener('click', function() {
        getChildRows().forEach(function(row) { row.style.display = 'none'; });
        document.querySelectorAll('.category-toggle').forEach(function(btn) {
            setToggleIcon(btn, false);
        });
    });
    document.querySelectorAll('.category-toggle').forEach(function(btn) {
        var parentId = btn.getAttribute('data-parent-id');
        btn.addEventListener('click', function() {
            var rows = document.querySelectorAll('.category-row.category-child[data-parent-id="' + parentId + '"]');
            var expanded = rows.length && rows[0].style.display !== 'none';
            var newExpanded = !expanded;
            rows.forEach(function(row) { row.style.display = newExpanded ? '' : 'none'; });
            setToggleIcon(btn, newExpanded);
        });
    });
})();
</script>
@endsection