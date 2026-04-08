<x-layouts.app :title="'Dashboard Monitoring | SIPADU'">
    <main class="px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-brand-300">Monitoring & Audit</p>
                    <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-white">Dashboard aktivitas SIPADU</h1>
                    <p class="mt-2 max-w-3xl text-sm text-slate-400">
                        Pantau performa portal, user paling aktif, dan aplikasi yang paling banyak diakses dari satu tempat.
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('portal.index') }}" class="inline-flex items-center rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-200 transition hover:border-brand-400/30 hover:text-white">
                        Portal utama
                    </a>
                    <a href="{{ route('users.index') }}" class="inline-flex items-center rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-200 transition hover:border-brand-400/30 hover:text-white">
                        Kelola user
                    </a>
                    <a href="{{ route('portal-applications.index') }}" class="inline-flex items-center rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-200 transition hover:border-brand-400/30 hover:text-white">
                        Kelola aplikasi
                    </a>
                </div>
            </div>

            <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <article class="section-panel rounded-[28px] p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-300">Aplikasi</p>
                    <p class="mt-3 text-3xl font-extrabold text-white">{{ number_format($summary['total_applications']) }}</p>
                    <p class="mt-2 text-sm text-slate-400">{{ number_format($summary['active_applications']) }} aktif, {{ number_format($summary['sso_applications']) }} SSO, {{ number_format($summary['launch_only_applications']) }} launch only</p>
                </article>
                <article class="section-panel rounded-[28px] p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-300">User</p>
                    <p class="mt-3 text-3xl font-extrabold text-white">{{ number_format($summary['total_users']) }}</p>
                    <p class="mt-2 text-sm text-slate-400">{{ number_format($summary['active_users_30d']) }} user aktif dalam 30 hari terakhir</p>
                </article>
                <article class="section-panel rounded-[28px] p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-300">Launch Hari Ini</p>
                    <p class="mt-3 text-3xl font-extrabold text-white">{{ number_format($summary['launches_today']) }}</p>
                    <p class="mt-2 text-sm text-slate-400">{{ number_format($summary['launches_7d']) }} launch dalam 7 hari terakhir</p>
                </article>
                <article class="section-panel rounded-[28px] p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-300">Audit 30 Hari</p>
                    <p class="mt-3 text-3xl font-extrabold text-white">{{ number_format($summary['launches_30d']) }}</p>
                    <p class="mt-2 text-sm text-slate-400">Total event launch terekam untuk monitoring dan audit</p>
                </article>
            </section>

            <section class="mt-8 grid gap-6 xl:grid-cols-[1.1fr_1fr]">
                <div class="section-panel rounded-[30px] p-6">
                    <div class="mb-5 flex items-end justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-300">Top Users</p>
                            <h2 class="mt-1 text-2xl font-bold text-white">User paling aktif</h2>
                        </div>
                        <p class="text-sm text-slate-500">Berdasarkan total launch</p>
                    </div>

                    <div class="space-y-3">
                        @forelse ($topUsers as $entry)
                            <div class="rounded-[24px] border border-white/8 bg-white/5 px-4 py-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-white">{{ $entry->user?->name ?? 'User dihapus' }}</p>
                                        <p class="mt-1 text-xs text-slate-400">
                                            {{ $entry->user?->employee_id ?? '-' }}
                                            @if ($entry->user?->division_name)
                                                · {{ $entry->user->division_name }}
                                            @endif
                                            @if ($entry->user?->title)
                                                · {{ $entry->user->title }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-2xl font-extrabold text-white">{{ number_format($entry->launches_count) }}</p>
                                        <p class="text-xs text-slate-500">launch</p>
                                    </div>
                                </div>
                                <p class="mt-3 text-xs text-slate-500">Terakhir aktif {{ \Illuminate\Support\Carbon::parse($entry->last_launched_at)->format('d M Y H:i') }}</p>
                            </div>
                        @empty
                            <div class="rounded-[24px] border border-dashed border-white/10 px-4 py-10 text-center text-sm text-slate-500">
                                Belum ada aktivitas launch yang terekam.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="section-panel rounded-[30px] p-6">
                    <div class="mb-5 flex items-end justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-300">Top Apps</p>
                            <h2 class="mt-1 text-2xl font-bold text-white">Aplikasi paling banyak diakses</h2>
                        </div>
                        <p class="text-sm text-slate-500">Berdasarkan audit launch</p>
                    </div>

                    <div class="space-y-3">
                        @forelse ($topApplications as $entry)
                            <div class="rounded-[24px] border border-white/8 bg-white/5 px-4 py-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-white">{{ $entry->application?->name ?? 'Aplikasi dihapus' }}</p>
                                        <p class="mt-1 text-xs text-slate-400">
                                            {{ $entry->application?->badge ?? 'Tanpa badge' }}
                                            @if ($entry->application?->launch_mode === 'sso')
                                                · SSO
                                            @elseif ($entry->application)
                                                · Launch only
                                            @endif
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-2xl font-extrabold text-white">{{ number_format($entry->launches_count) }}</p>
                                        <p class="text-xs text-slate-500">launch</p>
                                    </div>
                                </div>
                                <p class="mt-3 text-xs text-slate-500">Terakhir diakses {{ \Illuminate\Support\Carbon::parse($entry->last_launched_at)->format('d M Y H:i') }}</p>
                            </div>
                        @empty
                            <div class="rounded-[24px] border border-dashed border-white/10 px-4 py-10 text-center text-sm text-slate-500">
                                Belum ada aktivitas aplikasi yang bisa ditampilkan.
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>

            <section class="section-panel mt-8 overflow-hidden rounded-[30px]">
                <div class="flex items-end justify-between gap-3 px-6 pt-6">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-300">Recent Activity</p>
                        <h2 class="mt-1 text-2xl font-bold text-white">Aktivitas terbaru untuk audit</h2>
                    </div>
                    <p class="hidden text-sm text-slate-500 sm:block">12 event terakhir dari log launch SIPADU</p>
                </div>

                <div class="mt-5 overflow-x-auto">
                    <table class="admin-table min-w-full divide-y divide-white/6">
                        <thead>
                            <tr class="text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                                <th class="px-6 py-4">Waktu</th>
                                <th class="px-6 py-4">User</th>
                                <th class="px-6 py-4">Aplikasi</th>
                                <th class="px-6 py-4">Mode</th>
                                <th class="px-6 py-4">IP</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/6">
                            @forelse ($recentLaunches as $log)
                                <tr class="text-sm text-slate-300">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $log->launched_at?->format('d M Y H:i') }}</td>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-white">{{ $log->user?->name ?? 'User dihapus' }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $log->user?->employee_id ?? '-' }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-white">{{ $log->application?->name ?? 'Aplikasi dihapus' }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $log->application?->badge ?? 'Tanpa badge' }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="rounded-full {{ $log->application?->launch_mode === 'sso' ? 'bg-brand-400/10 text-brand-200' : 'bg-amber-400/10 text-amber-200' }} px-3 py-1 text-xs font-semibold">
                                            {{ $log->application?->launch_mode === 'sso' ? 'SSO' : 'Launch only' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-400">{{ $log->ip_address ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500">
                                        Belum ada audit trail launch untuk ditampilkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </main>
</x-layouts.app>
