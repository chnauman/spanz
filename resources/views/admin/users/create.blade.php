@extends('layouts.admin')

@section('title', 'Create User')

@section('content')
<div class="p-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create User</h1>
            <p class="text-gray-600 mt-2">Add a new user account</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Back to Users</a>
    </div>

    @if(session('error'))
        <div class="mb-4 rounded-md bg-red-50 p-4 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 rounded-md bg-red-50 p-4 text-red-800">
            <div class="font-semibold mb-2">Please fix the following:</div>
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role" id="role"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                        @php($role = old('role', 'buyer'))
                        <option value="buyer" {{ $role === 'buyer' ? 'selected' : '' }}>Buyer</option>
                        <option value="supplier" {{ $role === 'supplier' ? 'selected' : '' }}>Supplier</option>
                        <option value="sub_supplier" {{ $role === 'sub_supplier' ? 'selected' : '' }}>Colleague</option>
                        <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="guest" {{ $role === 'guest' ? 'selected' : '' }}>Guest</option>
                    </select>
                </div>

                <div id="parent-supplier-wrapper" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Parent Supplier (required for Colleague)</label>
                    <select name="parent_supplier_id" id="parent_supplier_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select a supplier...</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" {{ (string) old('parent_supplier_id') === (string) $s->id ? 'selected' : '' }}>
                                {{ $s->name }} ({{ $s->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input type="password" name="password" data-password-toggle-target
                            class="w-full pr-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        <button type="button" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700" data-password-toggle-btn aria-label="Show password">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" data-password-toggle-target
                            class="w-full pr-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        <button type="button" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700" data-password-toggle-btn aria-label="Show password">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="email_verified" value="1" {{ old('email_verified') ? 'checked' : '' }}>
                    <span class="text-sm text-gray-700">Mark email as verified</span>
                </label>

                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="is_approved" value="1" {{ old('is_approved', 1) ? 'checked' : '' }}>
                    <span class="text-sm text-gray-700">Approved (used for suppliers)</span>
                </label>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Create User
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2 bg-gray-200 text-gray-900 rounded-md hover:bg-gray-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleParentSupplier() {
        const role = document.getElementById('role')?.value;
        const wrap = document.getElementById('parent-supplier-wrapper');
        if (!wrap) return;
        wrap.classList.toggle('hidden', role !== 'sub_supplier');
    }
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('role')?.addEventListener('change', toggleParentSupplier);
        toggleParentSupplier();
        document.querySelectorAll('[data-password-toggle-btn]').forEach(function (button) {
            button.addEventListener('click', function () {
                const input = button.parentElement.querySelector('[data-password-toggle-target]');
                if (!input) return;
                input.type = input.type === 'password' ? 'text' : 'password';
            });
        });
    });
</script>
@endsection

