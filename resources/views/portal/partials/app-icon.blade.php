@switch($icon)
    @case('bank')
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5 12 5l9 5.5M5 10v8m14-8v8M3 19h18M9 19v-6h6v6" />
        </svg>
        @break
    @case('chart')
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16M7 16l3-3 3 2 5-6" />
        </svg>
        @break
    @case('shield')
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 3v5c0 4.2-2.6 8-7 10-4.4-2-7-5.8-7-10V6l7-3Z" />
        </svg>
        @break
    @case('users')
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16 19v-1a4 4 0 0 0-8 0v1M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm8 8v-1a3 3 0 0 0-3-3M4 19v-1a3 3 0 0 1 3-3" />
        </svg>
        @break
    @case('support')
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18 10a6 6 0 1 0-12 0v4a2 2 0 0 0 2 2h2l2 3 2-3h2a2 2 0 0 0 2-2v-4Z" />
        </svg>
        @break
    @case('box')
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="m12 3 8 4.5-8 4.5-8-4.5L12 3Zm8 4.5V16.5L12 21l-8-4.5V7.5M12 12v9" />
        </svg>
        @break
    @case('book')
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6.5A2.5 2.5 0 0 1 6.5 4H20v14H6.5A2.5 2.5 0 0 0 4 20.5v-14Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 4v14" />
        </svg>
        @break
    @default
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 3h6l5 5v13H8a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Zm5 1v5h5" />
        </svg>
@endswitch
