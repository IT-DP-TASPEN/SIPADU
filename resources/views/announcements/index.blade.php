<x-layouts.app :title="'Pengumuman | SIPADU'">
    <main class="px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-brand-300">Announcement Management</p>
                    <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-white">Kelola Pengumuman</h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('dashboard.index') }}" class="inline-flex items-center rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-200 transition hover:border-brand-400/30 hover:text-white">Dashboard</a>
                    <a href="{{ route('announcements.create') }}" class="inline-flex items-center rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-card transition hover:-translate-y-0.5 hover:bg-brand-700">Tambah Pengumuman</a>
                </div>
            </div>

            @if(session('status'))
                <div class="mb-5 rounded-2xl border border-brand-400/20 bg-brand-400/10 px-4 py-3 text-sm text-brand-100">{{ session('status') }}</div>
            @endif

            <div class="section-panel overflow-hidden rounded-[28px] shadow-soft">
                <div class="overflow-x-auto">
                    <table class="admin-table min-w-full divide-y divide-white/6">
                        <thead>
                            <tr class="text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                                <th class="px-5 py-4">Judul</th>
                                <th class="px-5 py-4">Prioritas</th>
                                <th class="px-5 py-4">Target</th>
                                <th class="px-5 py-4">Status</th>
                                <th class="px-5 py-4">Tayang</th>
                                <th class="px-5 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/6">
                            @forelse($announcements as $a)
                                <tr class="text-sm text-slate-300">
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-white">{{ $a->title }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ Str::limit(strip_tags($a->body), 60) }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold
                                            {{ $a->priority === 'high' ? 'bg-rose-400/10 text-rose-300' : ($a->priority === 'medium' ? 'bg-amber-400/10 text-amber-300' : 'bg-slate-400/10 text-slate-300') }}">
                                            {{ ucfirst($a->priority) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-xs">{{ ucfirst($a->target_type) }}</td>
                                    <td class="px-5 py-4">
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold
                                            {{ $a->status === 'published' ? 'bg-brand-400/10 text-brand-200' : ($a->status === 'archived' ? 'bg-slate-400/10 text-slate-400' : 'bg-white/5 text-slate-300') }}">
                                            {{ ucfirst($a->status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-xs">
                                        {{ $a->publish_at?->format('d M Y') ?? '-' }}
                                        @if($a->expire_at)
                                            <br><span class="text-slate-500">s/d {{ $a->expire_at->format('d M Y') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('announcements.edit', $a) }}" class="inline-flex items-center rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-xs font-semibold text-slate-200 transition hover:border-brand-400/30 hover:text-white">Edit</a>
                                            <form method="POST" action="{{ route('announcements.destroy', $a) }}" onsubmit="return confirm('Hapus pengumuman ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="inline-flex items-center rounded-xl border border-rose-500/20 bg-rose-500/5 px-3 py-2 text-xs font-semibold text-rose-300 transition hover:bg-rose-500/10">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-500">Belum ada pengumuman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-5">{{ $announcements->links() }}</div>
        </div>
    </main>
</x-layouts.app>
