<x-layouts.app :title="'SIPADU | Portal Terpadu'">
    <header class="relative z-40 px-4 pt-4 sm:px-6 lg:px-8">
        <div class="glass-panel mx-auto flex max-w-7xl items-center justify-between rounded-[28px] border border-white/8 px-5 py-4 shadow-soft sm:px-6">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-600 text-white shadow-card">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 10.5 12 4l8 6.5V19a1 1 0 0 1-1 1h-4.5v-5h-5v5H5a1 1 0 0 1-1-1v-8.5Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-brand-300">Bank DP Taspen</p>
                    <h1 class="text-lg font-extrabold tracking-tight text-white sm:text-xl">SIPADU</h1>
                    <p class="text-xs text-slate-400 sm:text-sm">Enterprise Portal Bank DP Taspen</p>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                @if ($user->isAdmin())
                    {{-- Dashboard quick link --}}
                    <a href="{{ route('dashboard.index') }}" class="hidden items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-semibold text-slate-200 transition hover:border-brand-400/30 hover:text-white md:inline-flex">
                        <svg class="h-4 w-4 text-brand-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v-5.5m3 5.5v-3.5m3 3.5v-1.5" /></svg>
                        Dashboard
                    </a>

                    {{-- Admin tools dropdown --}}
                    <div x-data="{ open: false }" class="relative hidden md:block">
                        <button @click="open = !open" class="flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-semibold text-slate-200 transition hover:border-brand-400/30 hover:text-white">
                            <svg class="h-4 w-4 text-brand-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" /></svg>
                            Admin
                            <svg class="h-3.5 w-3.5 text-slate-400 transition" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition
                            class="glass-panel absolute right-0 z-50 mt-3 w-60 rounded-[20px] border border-white/10 p-2 shadow-soft">
                            <p class="px-4 py-2 text-[11px] font-semibold uppercase tracking-widest text-slate-500">Manajemen</p>
                            <a href="{{ route('users.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-2.5 text-sm font-medium text-slate-200 transition hover:bg-white/5 hover:text-white">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                                Kelola User
                            </a>
                            <a href="{{ route('portal-applications.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-2.5 text-sm font-medium text-slate-200 transition hover:bg-white/5 hover:text-white">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0L21.75 16.5 12 21.75 2.25 16.5l4.179-2.25m0 0l5.571 3 5.571-3" /></svg>
                                Kelola Aplikasi
                            </a>
                            <div class="my-1 border-t border-white/5"></div>
                            <p class="px-4 py-2 text-[11px] font-semibold uppercase tracking-widest text-slate-500">Monitoring</p>
                            <a href="{{ route('sso-logs.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-2.5 text-sm font-medium text-slate-200 transition hover:bg-white/5 hover:text-white">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                                Log SSO
                            </a>
                            <a href="{{ route('audit-logs.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-2.5 text-sm font-medium text-slate-200 transition hover:bg-white/5 hover:text-white">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>
                                Audit Trail
                            </a>
                            <a href="{{ route('announcements.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-2.5 text-sm font-medium text-slate-200 transition hover:bg-white/5 hover:text-white">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38a.75.75 0 01-1.021-.278l-.303-.53a11.978 11.978 0 01-.764-1.542m2.223-2.324a11.22 11.22 0 001.08-3.51m0 0c.165-1.204.165-2.428 0-3.632m0 0a11.22 11.22 0 00-1.08-3.51M14.25 9.75V6.245c0-.76.594-1.4 1.352-1.42a14.18 14.18 0 011.458.018c.374.02.694.282.82.644a12.37 12.37 0 010 8.026c-.126.362-.446.624-.82.644a14.18 14.18 0 01-1.458.018c-.758-.02-1.352-.66-1.352-1.42z" /></svg>
                                Pengumuman
                            </a>
                        </div>
                    </div>
                @endif

                @include('components.notification-bell')

                <details class="relative">
                    <summary class="flex cursor-pointer list-none items-center gap-3 rounded-full border border-white/10 bg-white/5 px-2 py-2 text-left transition hover:border-brand-400/30">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-500/15 text-sm font-bold text-brand-200">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="hidden pr-2 sm:block">
                            <p class="text-xs text-slate-500">{{ $user->office_type === 'branch' ? 'Cabang' : 'Kantor Pusat' }}</p>
                            <p class="text-sm font-semibold text-white">Hi, {{ strtok($user->name, ' ') }}</p>
                        </div>
                    </summary>

                    <div class="glass-panel absolute right-0 z-50 mt-3 w-64 rounded-[24px] border border-white/10 p-2 shadow-soft">
                        <div class="rounded-[18px] border border-white/8 bg-white/5 px-4 py-3">
                            <p class="text-sm font-semibold text-white">{{ $user->name }}</p>
                            <p class="mt-1 text-xs text-slate-400">{{ $user->division_name ?: $user->unit_name }}</p>
                            <p class="text-xs text-slate-500">{{ $user->office_type === 'branch' ? 'Cabang' : 'Kantor Pusat' }}</p>
                        </div>
                        <div class="mt-2 space-y-1">
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/5 hover:text-white">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                Update Profil
                            </a>
                            @if ($user->isAdmin())
                                <div class="my-1 border-t border-white/5"></div>
                                <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/5 hover:text-white md:hidden">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5" /></svg>
                                    Dashboard
                                </a>
                                <a href="{{ route('users.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/5 hover:text-white md:hidden">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                                    Kelola User
                                </a>
                                <a href="{{ route('portal-applications.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/5 hover:text-white md:hidden">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0L21.75 16.5 12 21.75 2.25 16.5l4.179-2.25m0 0l5.571 3 5.571-3" /></svg>
                                    Kelola Aplikasi
                                </a>
                                <a href="{{ route('sso-logs.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/5 hover:text-white md:hidden">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                                    Log SSO
                                </a>
                                <a href="{{ route('audit-logs.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/5 hover:text-white md:hidden">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>
                                    Audit Trail
                                </a>
                                <a href="{{ route('announcements.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/5 hover:text-white md:hidden">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38a.75.75 0 01-1.021-.278l-.303-.53a11.978 11.978 0 01-.764-1.542m2.223-2.324a11.22 11.22 0 001.08-3.51m0 0c.165-1.204.165-2.428 0-3.632m0 0a11.22 11.22 0 00-1.08-3.51M14.25 9.75V6.245c0-.76.594-1.4 1.352-1.42a14.18 14.18 0 011.458.018c.374.02.694.282.82.644a12.37 12.37 0 010 8.026c-.126.362-.446.624-.82.644a14.18 14.18 0 01-1.458.018c-.758-.02-1.352-.66-1.352-1.42z" /></svg>
                                    Pengumuman
                                </a>
                            @endif
                            <div class="my-1 border-t border-white/5"></div>
                            <form id="portal-logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                                @csrf
                            </form>
                            <button
                                type="button"
                                onclick="event.preventDefault(); document.getElementById('portal-logout-form').submit();"
                                class="flex w-full items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-rose-200 transition hover:bg-rose-400/10"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" /></svg>
                                Logout
                            </button>
                        </div>
                    </div>
                </details>
            </div>
        </div>
    </header>

    <main class="px-4 pb-12 pt-8 sm:px-6 lg:px-8 lg:pt-10">
        <div class="mx-auto max-w-7xl">
            <section class="reveal relative mx-auto max-w-4xl px-4 py-8 text-center sm:py-12 z-10 flex-shrink-0">
                <!-- Bracket elements -->
                <div class="hologram-bracket bracket-left"></div>
                <div class="hologram-bracket bracket-right"></div>

                <div class="mx-auto mb-6 inline-flex items-center space-x-3 rounded-full border border-brand-500/30 bg-glow-pill px-6 py-2">
                    <div class="h-2.5 w-2.5 rounded-full bg-brand-400 shadow-[0_0_10px_rgba(40,147,83,0.8)] cyberpunk-glow"></div>
                    <span class="text-sm font-semibold uppercase tracking-[0.1em] text-brand-400">Enterprise Central</span>
                </div>
                <h2 class="mx-auto mb-6 text-5xl font-extrabold tracking-tight text-white glow-text-shadow text-glow-cyan md:text-7xl">
                    DP <span class="bg-clip-text text-transparent drop-shadow-sm bg-gradient-to-r from-brand-400 to-cyan-400">TASPEN</span> PORTAL
                </h2>
                <p class="mx-auto mt-4 max-w-2xl text-lg font-medium leading-relaxed text-slate-300 md:text-xl">
                    Pengguna tidak perlu menelusuri menu. Ketik kebutuhan Anda, lalu SIPADU mengarahkan ke aplikasi tujuan lewat single sign-on.
                </p>

                <div class="hero-search relative mx-auto mt-8 max-w-4xl rounded-[36px] p-4 shadow-soft sm:p-5">
                    <div class="flex flex-col gap-3 rounded-[28px] border border-white/8 bg-black/10 p-3 sm:flex-row sm:items-center sm:p-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-500/12 text-brand-300">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m1.85-5.15a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <label class="sr-only" for="global-search">Cari aplikasi, fitur, atau menu</label>
                            <input id="global-search" type="search" placeholder="Cari aplikasi, fitur, atau menu..." class="block w-full border-0 bg-transparent px-1 text-base font-medium text-white placeholder:text-slate-500 focus:ring-0 sm:text-lg" data-search-url="{{ route('portal.search') }}">
                        </div>
                        <div class="flex items-center justify-between gap-3 sm:justify-end">
                            <p id="search-feedback" class="text-xs text-slate-500 sm:text-sm">Temukan aplikasi lebih cepat dengan kata kunci.</p>
                            <div class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-xs font-semibold text-slate-400">Ctrl + K</div>
                        </div>
                    </div>

                    <div id="search-results" class="glass-panel mt-3 hidden rounded-[24px] border border-white/8 p-2 text-left shadow-card"></div>
                </div>
            </section>

            <section class="reveal mt-6">
                <div class="mb-5 flex items-end justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-brand-300">Frequent Apps</p>
                        <h3 class="mt-1 text-2xl font-bold text-white">Akses yang paling sering dipakai</h3>
                    </div>
                    <p class="hidden text-sm text-slate-500 sm:block">Diprioritaskan untuk kebutuhan operasional harian.</p>
                </div>
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    @foreach ($frequentApps as $application)
                        @include('portal.partials.app-card', ['application' => $application])
                    @endforeach
                </div>
            </section>

            <section class="reveal mt-12">
                <div class="mb-5 flex items-end justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-brand-300">All Applications</p>
                        <h3 class="mt-1 text-2xl font-bold text-white">Seluruh aplikasi internal ({{ $allApps->count() }})</h3>
                    </div>
                    <p id="all-apps-caption" class="hidden text-sm text-slate-500 sm:block">Gunakan pencarian untuk hasil tercepat, atau pilih dari daftar berikut.</p>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($allApps as $application)
                        @include('portal.partials.app-card', ['application' => $application])
                    @endforeach
                </div>
            </section>
        </div>
    </main>
</x-layouts.app>
