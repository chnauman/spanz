@auth
    @if(auth()->user()->isSupplier() || auth()->user()->isSubSupplier())
        <a href="{{ route('tenders.saved') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Saved Tenders</a>
        <a href="{{ route('company.register', ['mode' => 'edit']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Strengthen Profile</a>
        <a href="{{ route('user.rfx-received') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">RFXs Received</a>
        <a href="{{ route('user.messages') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">My Messages</a>
        <a href="{{ route('tenders.viewed') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Viewed Tenders</a>
    @else
        <a href="#" onclick="openSubscriptionModal(); return false;" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Become a Supplier</a>
    @endif
@else
    <a href="{{ route('login') }}?redirect={{ urlencode(request()->url()) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Become a Supplier</a>
@endauth
