@auth
    @if(!auth()->user()->isAdmin())
        <a href="{{ route('user.rfx-received') }}"
           class="relative inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white/10 hover:bg-white/20 transition-colors"
           title="New Projects / RFXs Received">
            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M4 20V10M9 20V4M14 20v-6M19 20V8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M3 20h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M19 8l-3-3-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            @if(($unreadRfxCount ?? 0) > 0)
                <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 flex items-center justify-center rounded-full bg-red-600 text-white text-[10px] font-bold leading-none">
                    {{ $unreadRfxCount > 99 ? '99+' : $unreadRfxCount }}
                </span>
            @endif
        </a>
        <a href="{{ route('user.messages') }}"
           class="relative inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white/10 hover:bg-white/20 transition-colors"
           title="My Messages">
            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            @if(($unreadMessagesCount ?? 0) > 0)
                <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 flex items-center justify-center rounded-full bg-red-600 text-white text-[10px] font-bold leading-none">
                    {{ $unreadMessagesCount > 99 ? '99+' : $unreadMessagesCount }}
                </span>
            @endif
        </a>
    @endif
@endauth
