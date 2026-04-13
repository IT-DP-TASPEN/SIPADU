<x-layouts.app :title="'Log SSO | SIPADU'">
    <main class="px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-brand-300">Integration Monitor</p>
                    <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-white">Log SSO SIPADU</h1>
                    <p class="mt-2 text-sm text-slate-400">Pantau histori launch, payload identitas, dan konfigurasi yang dikirim SIPADU saat proses integrasi ke aplikasi lain.</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('dashboard.index') }}" class="inline-flex items-center rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-200 transition hover:border-brand-400/30 hover:text-white">
                        Dashboard
                    </a>
                    <a href="{{ route('portal.index') }}" class="inline-flex items-center rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-200 transition hover:border-brand-400/30 hover:text-white">
                        Kembali ke portal
                    </a>
                </div>
            </div>

            <section class="mb-5 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                <article class="section-panel rounded-[24px] p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-300">Total Log</p>
                    <p class="mt-3 text-3xl font-extrabold text-white">{{ number_format($summary['total_logs']) }}</p>
                    <p class="mt-2 text-sm text-slate-500">Hasil filter saat ini</p>
                </article>
                <article class="section-panel rounded-[24px] p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-300">SSO</p>
                    <p class="mt-3 text-3xl font-extrabold text-white">{{ number_format($summary['sso_logs']) }}</p>
                    <p class="mt-2 text-sm text-slate-500">Launch dengan token SIPADU</p>
                </article>
                <article class="section-panel rounded-[24px] p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-300">Launch Only</p>
                    <p class="mt-3 text-3xl font-extrabold text-white">{{ number_format($summary['launch_only_logs']) }}</p>
                    <p class="mt-2 text-sm text-slate-500">Aplikasi yang tetap login mandiri</p>
                </article>
                <article class="section-panel rounded-[24px] p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-300">User</p>
                    <p class="mt-3 text-3xl font-extrabold text-white">{{ number_format($summary['unique_users']) }}</p>
                    <p class="mt-2 text-sm text-slate-500">User unik dalam hasil filter</p>
                </article>
                <article class="section-panel rounded-[24px] p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-300">Aplikasi</p>
                    <p class="mt-3 text-3xl font-extrabold text-white">{{ number_format($summary['unique_applications']) }}</p>
                    <p class="mt-2 text-sm text-slate-500">Aplikasi unik dalam hasil filter</p>
                </article>
            </section>

            <form method="GET" action="{{ route('sso-logs.index') }}" class="section-panel mb-5 rounded-[24px] p-4 shadow-sm">
                <div class="grid gap-3 xl:grid-cols-[1.2fr_0.7fr_0.6fr_0.55fr_0.55fr_auto]">
                    <x-forms.input name="q" value="{{ $search }}" placeholder="Cari nama user, employee ID, nama aplikasi, slug, URL, token ID..." />
                    <x-forms.select name="application_id">
                        <option value="">Semua aplikasi</option>
                        @foreach ($applications as $application)
                            <option value="{{ $application->id }}" @selected($applicationId === $application->id)>{{ $application->name }}</option>
                        @endforeach
                    </x-forms.select>
                    <x-forms.select name="launch_mode">
                        <option value="">Semua mode</option>
                        <option value="sso" @selected($launchMode === 'sso')>SSO</option>
                        <option value="launch_only" @selected($launchMode === 'launch_only')>Launch only</option>
                    </x-forms.select>
                    <x-forms.input type="date" name="date_from" value="{{ $dateFrom }}" />
                    <x-forms.input type="date" name="date_to" value="{{ $dateTo }}" />
                    <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-brand-600 px-5 py-3 text-sm font-semibold text-white shadow-card transition hover:bg-brand-700">
                        Filter
                    </button>
                </div>
                <p class="mt-3 text-xs text-slate-500">Log ini mencatat event launch dari SIPADU. Jika aplikasi tujuan masih kembali ke halaman login, buka detail event untuk memeriksa issuer, audience, employee ID, dan target URL yang dikirim.</p>
            </form>

            <div class="section-panel overflow-hidden rounded-[28px] shadow-soft">
                <div class="overflow-x-auto">
                    <table class="admin-table min-w-full divide-y divide-white/6">
                        <thead>
                            <tr class="text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                                <th class="px-5 py-4">Waktu</th>
                                <th class="px-5 py-4">User</th>
                                <th class="px-5 py-4">Aplikasi</th>
                                <th class="px-5 py-4">Mode</th>
                                <th class="px-5 py-4">Konfigurasi</th>
                                <th class="px-5 py-4">Target</th>
                                <th class="px-5 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/6">
                            @forelse ($logs as $log)
                                <tr class="text-sm text-slate-300">
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <p class="font-semibold text-white">{{ $log->launched_at?->format('d M Y H:i') }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $log->ip_address ?: '-' }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-white">{{ $log->user?->name ?? 'User dihapus' }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $log->user?->employee_id ?? '-' }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-white">{{ $log->application_name_snapshot ?: $log->application?->name ?: 'Aplikasi dihapus' }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $log->application_slug_snapshot ?: $log->application?->slug ?: '-' }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="rounded-full {{ $log->launch_mode_snapshot === 'sso' ? 'bg-brand-400/10 text-brand-200' : 'bg-amber-400/10 text-amber-200' }} px-3 py-1 text-xs font-semibold">
                                            {{ $log->launch_mode_snapshot === 'sso' ? 'SSO' : 'Launch only' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <p class="max-w-xs truncate text-xs text-slate-400">Issuer: {{ $log->issuer_snapshot ?: '-' }}</p>
                                        <p class="mt-1 max-w-xs truncate text-xs text-slate-500">Audience: {{ $log->audience_snapshot ?: '-' }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <p class="max-w-sm truncate">{{ $log->target_url }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $log->token_id ?: 'Tanpa token' }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <a href="{{ route('sso-logs.show', $log) }}" class="inline-flex items-center rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-xs font-semibold text-slate-200 transition hover:border-brand-400/30 hover:text-white">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-10 text-center text-sm text-slate-500">
                                        Belum ada log SSO yang terekam.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5">
                {{ $logs->links() }}
            </div>
        </div>
    </main>
</x-layouts.app>
