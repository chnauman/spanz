@php
    $isCompact = isset($compact) && $compact;
@endphp
<div class="mx-auto max-w-6xl rounded-xl px-2 py-3 {{ $isCompact ? 'mt-4' : 'mt-8' }}">
    <ul class="list-outside list-disc space-y-2 pl-5 {{ $isCompact ? 'text-sm sm:text-base leading-relaxed' : 'text-sm sm:text-base leading-relaxed' }} text-gray-700">
        <li>While serving globally, SPANZ is based in Australia. We provide full email and phone support to our members during regular business hours.</li>
        <li>We offer <strong class="font-semibold text-gray-900">FREE</strong> 30-day trial, no question asked if you decide to cancel during trial or afterwards. A refund of the unused amount will be given. (20% administrative fee may apply)</li>
        <li>No credit card information is required. Accounts are invoiced every 3-months, GST, VAT or other government tax, if applicable, may apply.</li>
        <li>SPANZ reserves the right to refuse membership to anyone at its sole discretion.</li>
        <li>You agree to SPANZ Terms and Conditions. See <a href="{{ route('terms') }}" target="_blank" rel="noopener" class="font-semibold text-blue-700 underline hover:text-blue-800">Terms &amp; Conditions</a>.</li>
        <li>Prices are subject to change.</li>
    </ul>
</div>
