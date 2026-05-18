<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colleague Invitation - SPANZ</title>
    <link rel="stylesheet" href="{{ asset('css/output.css') }}">
</head>
<body>
<div class="bg-image w-full min-h-screen bg-cover bg-center bg-no-repeat flex flex-col" style="background-image:url('{{ asset('spanz-img/spanz-bg.jpg') }}')">
    <div class="flex-1 flex items-center justify-center px-4 py-12">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-8">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-[#0D6AED]">SPANZ</h1>
                <p class="text-gray-600 mt-2">Colleague Invitation</p>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-gray-800">
                    <strong>{{ $invitation->supplier->name }}</strong> has invited you to join SPANZ as a colleague for
                    <strong>{{ $invitation->supplier->companyDetail?->company_name ?? 'their company' }}</strong>.
                </p>
                @if($invitation->message)
                    <p class="text-sm text-gray-600 mt-3 italic">"{{ $invitation->message }}"</p>
                @endif
            </div>

            <ul class="text-sm text-gray-600 space-y-2 mb-6">
                <li>• You will use your company email: <strong>{{ $invitation->email }}</strong></li>
                <li>• Company profile is shared with {{ $invitation->supplier->name }}'s organisation</li>
                <li>• You share the same subscription and credits as your team</li>
            </ul>

            <p class="text-xs text-gray-500 mb-6">This invitation expires on {{ $invitation->expires_at->format('M d, Y \a\t g:i A') }}.</p>

            <form method="POST" action="{{ route('supplier.invitation.accept', $invitation->token) }}">
                @csrf
                <button type="submit" class="w-full bg-[#0D6AED] text-white py-3 rounded-md font-medium hover:bg-blue-700 transition-colors">
                    Accept Invitation &amp; Register
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-4">
                Already have an account? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Log in</a>
            </p>
        </div>
    </div>
</div>
</body>
</html>
