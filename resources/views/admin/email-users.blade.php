@extends('layouts.admin')

@section('title', 'Message User')

@push('styles')
<style>
    .user-picker {
        position: relative;
    }
    .user-picker-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        min-height: 2.5rem;
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        background: #fff;
        margin-bottom: 0.5rem;
    }
    .user-picker-tags:empty::before {
        content: 'No users selected';
        color: #9ca3af;
        font-size: 0.875rem;
    }
    .user-picker-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.5rem 0.25rem 0.65rem;
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #93c5fd;
        border-radius: 9999px;
        font-size: 0.8125rem;
        font-weight: 500;
        line-height: 1.25;
    }
    .user-picker-tag button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 1.125rem;
        height: 1.125rem;
        border: none;
        border-radius: 9999px;
        background: transparent;
        color: #1d4ed8;
        cursor: pointer;
        padding: 0;
        line-height: 1;
    }
    .user-picker-tag button:hover {
        background: rgba(29, 78, 216, 0.12);
    }
    .user-picker-search {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 0.875rem;
    }
    .user-picker-search:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25);
    }
    .user-picker-results {
        position: absolute;
        left: 0;
        right: 0;
        z-index: 30;
        margin-top: 0.25rem;
        max-height: 14rem;
        overflow-y: auto;
        background: #fff;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        display: none;
    }
    .user-picker-results.is-open {
        display: block;
    }
    .user-picker-option {
        display: block;
        width: 100%;
        text-align: left;
        padding: 0.625rem 0.75rem;
        border: none;
        background: #fff;
        cursor: pointer;
        border-bottom: 1px solid #f3f4f6;
    }
    .user-picker-option:last-child {
        border-bottom: none;
    }
    .user-picker-option:hover,
    .user-picker-option:focus {
        background: #eff6ff;
        outline: none;
    }
    .user-picker-option.is-selected {
        opacity: 0.55;
        cursor: default;
    }
    .user-picker-option-name {
        font-size: 0.875rem;
        font-weight: 600;
        color: #111827;
    }
    .user-picker-option-email {
        font-size: 0.8125rem;
        color: #6b7280;
    }
    .user-picker-option-meta {
        font-size: 0.75rem;
        color: #9ca3af;
        margin-top: 0.125rem;
    }
    .user-picker-empty {
        padding: 0.75rem;
        font-size: 0.875rem;
        color: #6b7280;
        text-align: center;
    }
</style>
@endpush

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Message User</h1>
        <p class="text-gray-600 mt-2">Send a message from the no-reply address to all users or to a selection. Recipients cannot reply to this email.</p>
        <p class="text-sm text-gray-500 mt-1">From: <strong>{{ config('mail.noreply.address') }}</strong> ({{ config('mail.noreply.name') }})</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-6">{{ session('success') }}</div>
    @endif
    @if(session('info'))
        <div class="bg-blue-100 border border-blue-400 text-blue-800 px-4 py-3 rounded mb-6">{{ session('info') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded mb-6">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.email-users.send') }}" id="admin-email-form" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Recipients</label>
                <div class="space-y-3">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="radio" name="recipient_mode" value="all" class="mt-1" {{ old('recipient_mode', 'all') === 'all' ? 'checked' : '' }}>
                        <span>
                            <span class="font-medium text-gray-900">All users</span>
                            <span class="block text-sm text-gray-500">Everyone with an email on file ({{ $allRecipientCount }} users).</span>
                        </span>
                    </label>
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="radio" name="recipient_mode" value="selected" class="mt-1" id="mode-selected" {{ old('recipient_mode') === 'selected' ? 'checked' : '' }}>
                        <span>
                            <span class="font-medium text-gray-900">Selected users</span>
                            <span class="block text-sm text-gray-500">Search by name or email, then click to add recipients.</span>
                        </span>
                    </label>
                </div>
                @error('recipient_mode')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="user-select-wrap" class="{{ old('recipient_mode') === 'selected' ? '' : 'hidden' }}">
                <label class="block text-sm font-medium text-gray-700 mb-1">Users</label>
                <div class="user-picker" id="user-picker">
                    <div id="user-picker-tags" class="user-picker-tags" aria-live="polite"></div>
                    <div id="user-picker-hidden-inputs"></div>
                    <input type="text"
                           id="user-search"
                           class="user-picker-search"
                           placeholder="Search by name or email..."
                           autocomplete="off"
                           aria-label="Search users">
                    <div id="user-picker-results" class="user-picker-results" role="listbox"></div>
                </div>
                @error('user_ids')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                @error('user_ids.*')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required maxlength="255"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Email subject line">
                @error('subject')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="body" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                <textarea name="body" id="body" rows="12" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Type your message here. Line breaks will be preserved in the email.">{{ old('body') }}</textarea>
                @error('body')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Send
                </button>
                <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900 text-sm">Back to users</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function () {
    var allUsers = @json($usersForPicker);
    var initialSelected = @json($selectedUserIds);
    var selected = new Map();

    var modeSelected = document.getElementById('mode-selected');
    var radios = document.querySelectorAll('input[name="recipient_mode"]');
    var wrap = document.getElementById('user-select-wrap');
    var tagsEl = document.getElementById('user-picker-tags');
    var hiddenEl = document.getElementById('user-picker-hidden-inputs');
    var searchEl = document.getElementById('user-search');
    var resultsEl = document.getElementById('user-picker-results');
    var pickerEl = document.getElementById('user-picker');

    function userById(id) {
        var target = parseInt(id, 10);
        return allUsers.find(function (u) { return u.id === target; });
    }

    function syncRecipientMode() {
        if (!wrap) return;
        wrap.classList.toggle('hidden', !(modeSelected && modeSelected.checked));
    }

    radios.forEach(function (r) { r.addEventListener('change', syncRecipientMode); });
    syncRecipientMode();

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text || '';
        return div.innerHTML;
    }

    function renderSelected() {
        if (!tagsEl || !hiddenEl) return;
        tagsEl.innerHTML = '';
        hiddenEl.innerHTML = '';

        selected.forEach(function (user) {
            var tag = document.createElement('span');
            tag.className = 'user-picker-tag';
            tag.innerHTML = '<span>' + escapeHtml(user.name) + '</span>' +
                '<button type="button" aria-label="Remove ' + escapeHtml(user.name) + '" data-remove-id="' + user.id + '">&times;</button>';
            tagsEl.appendChild(tag);

            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'user_ids[]';
            input.value = user.id;
            hiddenEl.appendChild(input);
        });
    }

    function addUser(user) {
        if (!user || selected.has(user.id)) return;
        selected.set(user.id, user);
        renderSelected();
        renderResults(searchEl ? searchEl.value : '');
    }

    function removeUser(id) {
        selected.delete(id);
        renderSelected();
        renderResults(searchEl ? searchEl.value : '');
    }

    function sortUsers(users) {
        return users.slice().sort(function (a, b) {
            if (a.is_admin !== b.is_admin) {
                return a.is_admin ? -1 : 1;
            }
            return (a.name || '').localeCompare(b.name || '', undefined, { sensitivity: 'base' });
        });
    }

    function filterUsers(query) {
        var q = (query || '').trim().toLowerCase();
        var list = allUsers;

        if (q) {
            list = allUsers.filter(function (u) {
                var name = (u.name || '').toLowerCase();
                var email = (u.email || '').toLowerCase();
                var role = (u.role || '').toLowerCase();
                return name.indexOf(q) !== -1 || email.indexOf(q) !== -1 || role.indexOf(q) !== -1;
            });
        }

        return sortUsers(list);
    }

    function renderResults(query) {
        if (!resultsEl) return;
        var matches = filterUsers(query).slice(0, 50);

        resultsEl.innerHTML = '';

        if (!matches.length) {
            resultsEl.innerHTML = '<div class="user-picker-empty">No users match your search.</div>';
            resultsEl.classList.add('is-open');
            return;
        }

        matches.forEach(function (user) {
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'user-picker-option' + (selected.has(user.id) ? ' is-selected' : '');
            btn.setAttribute('role', 'option');
            btn.dataset.userId = user.id;

            var emailHtml = user.email
                ? escapeHtml(user.email)
                : '<span class="text-amber-600">No email on file</span>';

            btn.innerHTML =
                '<div class="user-picker-option-name">' + escapeHtml(user.name) + '</div>' +
                '<div class="user-picker-option-email">' + emailHtml + '</div>' +
                '<div class="user-picker-option-meta">' + escapeHtml(user.role) + (user.is_admin ? ' · Admin' : '') + '</div>';

            if (!selected.has(user.id)) {
                btn.addEventListener('click', function () {
                    addUser(user);
                    if (searchEl) {
                        searchEl.value = '';
                        searchEl.focus();
                    }
                    renderResults('');
                });
            }

            resultsEl.appendChild(btn);
        });

        resultsEl.classList.add('is-open');
    }

    if (tagsEl) {
        tagsEl.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-remove-id]');
            if (!btn) return;
            removeUser(parseInt(btn.dataset.removeId, 10));
        });
    }

    if (searchEl) {
        searchEl.addEventListener('input', function () {
            renderResults(searchEl.value);
        });
        searchEl.addEventListener('focus', function () {
            renderResults(searchEl.value);
        });
    }

    document.addEventListener('click', function (e) {
        if (!pickerEl || pickerEl.contains(e.target)) return;
        if (resultsEl) resultsEl.classList.remove('is-open');
    });

    initialSelected.forEach(function (id) {
        var user = userById(id);
        if (user) selected.set(user.id, user);
    });
    renderSelected();

    var form = document.getElementById('admin-email-form');
    if (form) {
        form.addEventListener('submit', function (e) {
            var mode = document.querySelector('input[name="recipient_mode"]:checked');
            if (!mode) return;
            var count = 0;
            if (mode.value === 'all') {
                count = {{ (int) $allRecipientCount }};
            } else {
                count = selected.size;
            }
            if (mode.value === 'selected' && count === 0) {
                e.preventDefault();
                alert('Please select at least one user, or choose "All users".');
                return;
            }
            if (!confirm('Send this message to ' + count + ' recipient(s)?')) {
                e.preventDefault();
            }
        });
    }
})();
</script>
@endpush
@endsection
