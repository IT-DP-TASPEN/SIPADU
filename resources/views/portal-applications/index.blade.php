<x-layouts.app :title="'Kelola Aplikasi | SIPADU'">
    <main class="px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-brand-300">Portal Configuration</p>
                    <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-white">Kelola aplikasi SIPADU</h1>
                    <p class="mt-2 text-sm text-slate-400">Atur metadata aplikasi, mode launch, dan endpoint SSO dari satu tempat.</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('dashboard.index') }}" class="inline-flex items-center rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-200 shadow-sm transition hover:border-brand-400/30 hover:text-white">
                        Dashboard
                    </a>
                    <a href="{{ route('users.index') }}" class="inline-flex items-center rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-200 shadow-sm transition hover:border-brand-400/30 hover:text-white">
                        Kelola user
                    </a>
                    <a href="{{ route('portal.index') }}" class="inline-flex items-center rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-200 shadow-sm transition hover:border-brand-400/30 hover:text-white">
                        Kembali ke portal
                    </a>
                    <a href="{{ route('portal-applications.create') }}" class="inline-flex items-center rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-card transition hover:-translate-y-0.5 hover:bg-brand-700">
                        Tambah aplikasi
                    </a>
                </div>
            </div>

            @if (session('status'))
                <div class="mb-5 rounded-2xl border border-brand-400/20 bg-brand-400/10 px-4 py-3 text-sm text-brand-100">
                    {{ session('status') }}
                </div>
            @endif

            <div class="section-panel overflow-hidden rounded-[28px]">
                <div class="overflow-x-auto">
                    <table class="admin-table min-w-full divide-y divide-white/6">
                        <thead>
                            <tr class="text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                                <th class="px-5 py-4">Aplikasi</th>
                                <th class="px-5 py-4">Mode</th>
                                <th class="px-5 py-4">URL</th>
                                <th class="px-5 py-4">Status</th>
                                <th class="px-5 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/6">
                            @foreach ($applications as $application)
                                <tr class="text-sm text-slate-300">
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-white">{{ $application->name }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $application->slug }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="rounded-full {{ $application->usesSso() ? 'bg-brand-400/10 text-brand-200' : 'bg-amber-400/10 text-amber-200' }} px-3 py-1 text-xs font-semibold">
                                            {{ $application->usesSso() ? 'SSO' : 'Launch only' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <p class="max-w-xs truncate">{{ $application->url }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            @if ($application->is_active)
                                                <span class="rounded-full bg-emerald-400/10 px-3 py-1 text-xs font-semibold text-emerald-200">Aktif</span>
                                            @endif
                                            @if ($application->is_frequent)
                                                <span class="rounded-full bg-sky-400/10 px-3 py-1 text-xs font-semibold text-sky-200">Frequent</span>
                                            @endif
                                            @if ($application->accessRules()->exists())
                                                <span class="rounded-full bg-violet-400/10 px-3 py-1 text-xs font-semibold text-violet-200">
                                                    {{ $application->accessRules()->count() }} rule akses
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('portal-applications.edit', $application) }}" class="inline-flex items-center rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-xs font-semibold text-slate-200 transition hover:border-brand-400/30 hover:text-white">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('portal-applications.destroy', $application) }}" onsubmit="return confirm('Hapus aplikasi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center rounded-xl border border-rose-400/20 bg-white/5 px-3 py-2 text-xs font-semibold text-rose-200 transition hover:bg-rose-400/10">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</x-layouts.app>
