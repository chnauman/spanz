@auth
    @include('partials.header-notification-icons')
    <a href="{{ route('dashboard') }}" class="border border-white text-white px-3 py-1 rounded hover:bg-white hover:text-black">
        Dashboard
    </a>
    <form method="POST" action="{{ route('logout') }}" class="inline">
        @csrf
        <button type="submit" class="bg-blue-700 text-white px-3 py-1 rounded hover:bg-blue-800">
            Logout
        </button>
    </form>
@else
    <a href="{{ route('login') }}" class="border border-white text-white px-3 py-1 rounded hover:bg-white hover:text-black">
        Login
    </a>
    <a href="{{ route('register') }}" class="bg-blue-700 text-white px-3 py-1 rounded hover:bg-blue-800">
        Register
    </a>
@endauth
