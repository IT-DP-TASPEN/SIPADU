<x-layouts.app :title="'SIPADU | Portal Terpadu'">
    <header class="px-4 pt-4 sm:px-6 lg:px-8">
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

            <div class="flex items-center gap-3 sm:gap-4">
                @if ($user->isAdmin())
                    <a href="{{ route('dashboard.index') }}" class="hidden items-center rounded-2xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-slate-200 transition hover:border-brand-400/30 hover:text-white md:inline-flex">
                        Dashboard
                    </a>
                    <a href="{{ route('users.index') }}" class="hidden items-center rounded-2xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-slate-200 transition hover:border-brand-400/30 hover:text-white md:inline-flex">
                        Kelola user
                    </a>
                    <a href="{{ route('portal-applications.index') }}" class="hidden items-center rounded-2xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-slate-200 transition hover:border-brand-400/30 hover:text-white md:inline-flex">
                        Kelola aplikasi
                    </a>
                @endif
                <details class="relative">
                    <summary class="flex cursor-pointer list-none items-center gap-3 rounded-full border border-white/10 bg-white/5 px-2 py-2 text-left transition hover:border-brand-400/30">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-500/15 text-sm font-bold text-brand-200">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="hidden pr-2 sm:block">
                            <p class="text-xs text-slate-500">{{ $user->office_type === 'branch' ? 'Cabang' : 'Kantor Pusat' }}</p>
                            <p class="text-sm font-semibold text-white">Hi, {{ strtok($user->name, ' ') }}</p>
                        </div>
                    </summary>

                    <div class="glass-panel absolute right-0 z-20 mt-3 w-64 rounded-[24px] border border-white/10 p-2 shadow-soft">
                        <div class="rounded-[18px] border border-white/8 bg-white/5 px-4 py-3">
                            <p class="text-sm font-semibold text-white">{{ $user->name }}</p>
                            <p class="mt-1 text-xs text-slate-400">{{ $user->division_name ?: $user->unit_name }}</p>
                            <p class="text-xs text-slate-500">{{ $user->office_type === 'branch' ? 'Cabang' : 'Kantor Pusat' }}</p>
                        </div>
                        <div class="mt-2 space-y-1">
                            @if ($user->isAdmin())
                                <a href="{{ route('dashboard.index') }}" class="flex items-center rounded-2xl px-4 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/5 hover:text-white md:hidden">
                                    Dashboard monitoring
                                </a>
                            @endif
                            <a href="{{ route('profile.edit') }}" class="flex items-center rounded-2xl px-4 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/5 hover:text-white">
                                Update profil
                            </a>
                            <form id="portal-logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                                @csrf
                            </form>
                            <button
                                type="button"
                                onclick="event.preventDefault(); document.getElementById('portal-logout-form').submit();"
                                class="flex w-full items-center rounded-2xl px-4 py-3 text-sm font-semibold text-rose-200 transition hover:bg-rose-400/10"
                            >
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
            <section class="reveal mx-auto max-w-4xl px-2 py-8 text-center sm:py-12">
                <div class="mx-auto mb-5 inline-flex items-center gap-2 rounded-full border border-brand-100 bg-white/80 px-4 py-2 text-xs font-semibold text-brand-700 shadow-soft">
                    <span class="h-2 w-2 rounded-full bg-brand-500"></span>
                    SSO gateway untuk seluruh aplikasi internal
                </div>
                <h2 class="mx-auto max-w-3xl text-3xl font-extrabold tracking-tight text-white sm:text-5xl">
                    Cari aplikasi, fitur, atau menu yang Anda butuhkan.
                </h2>
                <p class="mx-auto mt-4 max-w-2xl text-sm leading-6 text-slate-400 sm:text-base">
                    Pengguna tidak perlu menelusuri menu. Ketik kebutuhan Anda, lalu SIPADU mengarahkan ke aplikasi tujuan melalui single sign-on.
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
                        <h3 class="mt-1 text-2xl font-bold text-white">Seluruh aplikasi internal</h3>
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
