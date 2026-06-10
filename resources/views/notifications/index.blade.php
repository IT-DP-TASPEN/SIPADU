<x-layouts.app :title="'Notifikasi | SIPADU'">
    <main class="px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-4xl">
            <div class="mb-6 flex items-end justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-brand-300">Notification Center</p>
                    <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-white">Notifikasi</h1>
                </div>
                @if($notifications->where('is_read', false)->count() > 0)
                    <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-200 transition hover:border-brand-400/30 hover:text-white">
                            Tandai Semua Dibaca
                        </button>
                    </form>
                @endif
            </div>

            <div class="section-panel rounded-[28px] divide-y divide-white/6">
                @forelse($notifications as $notification)
                    <div class="flex items-start gap-4 px-6 py-5 {{ $notification->is_read ? '' : 'bg-brand-500/5' }}">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl
                            {{ $notification->type === 'security' ? 'bg-rose-500/20 text-rose-300' : ($notification->type === 'announcement' ? 'bg-cyan-500/20 text-cyan-300' : 'bg-amber-500/20 text-amber-300') }}">
                            @if($notification->type === 'security')
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                                </svg>
                            @elseif($notification->type === 'announcement')
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 001.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 010 3.46" />
                                </svg>
                            @else
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold {{ $notification->is_read ? 'text-slate-300' : 'text-white' }}">{{ $notification->title }}</p>
                            @if($notification->body)
                                <p class="mt-1 text-sm text-slate-400">{{ $notification->body }}</p>
                            @endif
                            <p class="mt-2 text-xs text-slate-500">{{ $notification->created_at?->format('d M Y H:i') }} &middot; {{ $notification->created_at?->diffForHumans() }}</p>
                        </div>
                        @if(!$notification->is_read)
                            <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                @csrf
                                <button type="submit" class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-xs font-semibold text-slate-300 transition hover:border-brand-400/30 hover:text-white">
                                    Baca
                                </button>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="px-6 py-12 text-center text-sm text-slate-500">
                        Belum ada notifikasi.
                    </div>
                @endforelse
            </div>

            <div class="mt-5">
                {{ $notifications->links() }}
            </div>
        </div>
    </main>
</x-layouts.app>
