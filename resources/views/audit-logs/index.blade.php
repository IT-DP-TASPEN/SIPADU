<x-layouts.app :title="'Audit Trail | SIPADU'">
    <main class="px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-brand-300">Audit Trail</p>
                    <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-white">Log Aktivitas Sistem</h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('dashboard.index') }}" class="inline-flex items-center rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-200 transition hover:border-brand-400/30 hover:text-white">Dashboard</a>
                </div>
            </div>

            <form method="GET" action="{{ route('audit-logs.index') }}" class="section-panel mb-5 rounded-[24px] p-4 shadow-sm">
                <div class="flex flex-col gap-3 sm:flex-row">
                    <x-forms.input name="q" value="{{ $search }}" placeholder="Cari user, aktivitas, deskripsi, IP..." />
                    <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-brand-600 px-5 py-3 text-sm font-semibold text-white shadow-card transition hover:bg-brand-700">Cari</button>
                </div>
            </form>

            <div class="section-panel overflow-hidden rounded-[28px] shadow-soft">
                <div class="overflow-x-auto">
                    <table class="admin-table min-w-full divide-y divide-white/6">
                        <thead>
                            <tr class="text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                                <th class="px-5 py-4">Waktu</th>
                                <th class="px-5 py-4">User</th>
                                <th class="px-5 py-4">Aktivitas</th>
                                <th class="px-5 py-4">IP</th>
                                <th class="px-5 py-4">Browser / Device</th>
                                <th class="px-5 py-4">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/6">
                            @forelse($logs as $log)
                                <tr class="text-sm text-slate-300">
                                    <td class="px-5 py-4 whitespace-nowrap text-xs">{{ $log->created_at?->format('d M Y H:i:s') }}</td>
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-white">{{ $log->user_name ?? '-' }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="rounded-full bg-white/5 px-3 py-1 text-xs font-semibold text-slate-200">{{ $log->activity }}</span>
                                    </td>
                                    <td class="px-5 py-4 text-xs text-slate-400">{{ $log->ip_address ?? '-' }}</td>
                                    <td class="px-5 py-4 text-xs">{{ $log->browser ?? '-' }} / {{ $log->device ?? '-' }}</td>
                                    <td class="px-5 py-4 text-xs text-slate-400 max-w-xs truncate">{{ $log->description ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-500">Belum ada log aktivitas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-5">{{ $logs->links() }}</div>
        </div>
    </main>
</x-layouts.app>
