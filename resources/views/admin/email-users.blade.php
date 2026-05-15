@extends('layouts.admin')

@section('title', 'Email Users')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Email Users</h1>
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
                            <span class="block text-sm text-gray-500">Choose one or more users below.</span>
                        </span>
                    </label>
                </div>
                @error('recipient_mode')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="user-select-wrap" class="{{ old('recipient_mode') === 'selected' ? '' : 'hidden' }}">
                <label for="user_ids" class="block text-sm font-medium text-gray-700 mb-1">Users (hold Ctrl or Cmd to select multiple)</label>
                <select name="user_ids[]" id="user_ids" multiple size="12"
                        class="w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 font-mono text-sm">
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ collect(old('user_ids', []))->contains($u->id) ? 'selected' : '' }}>
                            {{ $u->name }} &lt;{{ $u->email }}&gt; — {{ $u->role }}
                        </option>
                    @endforeach
                </select>
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
                    Send email
                </button>
                <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900 text-sm">Back to users</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function () {
    var modeSelected = document.getElementById('mode-selected');
    var radios = document.querySelectorAll('input[name="recipient_mode"]');
    var wrap = document.getElementById('user-select-wrap');
    function sync() {
        if (!wrap) return;
        wrap.classList.toggle('hidden', !(modeSelected && modeSelected.checked));
    }
    radios.forEach(function (r) { r.addEventListener('change', sync); });
    sync();

    var form = document.getElementById('admin-email-form');
    if (form) {
        form.addEventListener('submit', function (e) {
            var mode = document.querySelector('input[name="recipient_mode"]:checked');
            if (!mode) return;
            var count = 0;
            if (mode.value === 'all') {
                count = {{ (int) $allRecipientCount }};
            } else {
                var sel = document.getElementById('user_ids');
                count = sel ? sel.selectedOptions.length : 0;
            }
            if (mode.value === 'selected' && count === 0) {
                e.preventDefault();
                alert('Please select at least one user, or choose "All users".');
                return;
            }
            if (!confirm('Send this email to ' + count + ' recipient(s)?')) {
                e.preventDefault();
            }
        });
    }
})();
</script>
@endpush
@endsection
