{{-- Notification bell dropdown for portal header --}}
@php
    $unreadCount = \App\Models\Notification::query()
        ->where(function($q) {
            $q->where('user_id', auth()->id())->orWhereNull('user_id');
        })
        ->where('is_read', false)
        ->count();
    $recentNotifications = \App\Models\Notification::query()
        ->where(function($q) {
            $q->where('user_id', auth()->id())->orWhereNull('user_id');
        })
        ->orderByDesc('created_at')
        ->limit(5)
        ->get();
@endphp

<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" class="relative flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/5 text-slate-300 transition hover:border-brand-400/30 hover:text-white">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
        </svg>
        @if($unreadCount > 0)
            <span class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-rose-500 text-[10px] font-bold text-white">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <div x-show="open" @click.away="open = false" x-transition
        class="glass-panel absolute right-0 z-30 mt-3 w-80 rounded-[24px] border border-white/10 p-2 shadow-soft sm:w-96">
        <div class="flex items-center justify-between rounded-[18px] border border-white/8 bg-white/5 px-4 py-3">
            <p class="text-sm font-semibold text-white">Notifikasi</p>
            @if($unreadCount > 0)
                <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                    @csrf
                    <button type="submit" class="text-xs font-semibold text-brand-300 hover:text-brand-200">Tandai semua dibaca</button>
                </form>
            @endif
        </div>

        <div class="mt-2 max-h-80 overflow-y-auto space-y-1">
            @forelse($recentNotifications as $notification)
                <div class="rounded-[18px] px-4 py-3 transition {{ $notification->is_read ? 'bg-transparent' : 'bg-brand-500/5 border border-brand-500/10' }}">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1">
                            <p class="text-sm font-semibold {{ $notification->is_read ? 'text-slate-300' : 'text-white' }}">
                                {{ $notification->title }}
                            </p>
                            @if($notification->body)
                                <p class="mt-1 text-xs text-slate-400 line-clamp-2">{{ $notification->body }}</p>
                            @endif
                            <p class="mt-1 text-xs text-slate-500">{{ $notification->created_at?->diffForHumans() }}</p>
                        </div>
                        @if(!$notification->is_read)
                            <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                @csrf
                                <button type="submit" class="text-xs text-brand-300 hover:text-brand-200" title="Tandai dibaca">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-[18px] px-4 py-8 text-center text-sm text-slate-500">
                    Tidak ada notifikasi.
                </div>
            @endforelse
        </div>

        <div class="mt-2">
            <a href="{{ route('notifications.index') }}" class="block rounded-[18px] px-4 py-3 text-center text-sm font-semibold text-brand-300 transition hover:bg-white/5 hover:text-brand-200">
                Lihat Semua Notifikasi
            </a>
        </div>
    </div>
</div>
