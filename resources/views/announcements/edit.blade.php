<x-layouts.app :title="'Edit Pengumuman | SIPADU'">
    <main class="px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-4xl">
            <div class="mb-6">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-brand-300">Announcement Management</p>
                <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-white">Edit Pengumuman</h1>
                <p class="mt-2 text-sm text-slate-400">{{ $announcement->title }}</p>
            </div>

            <form method="POST" action="{{ route('announcements.update', $announcement) }}" class="section-panel p-6 sm:p-8">
                @csrf @method('PUT')
                @include('announcements._form')

                <div class="mt-8 flex gap-3">
                    <a href="{{ route('announcements.index') }}" class="inline-flex items-center rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-200 transition hover:border-brand-400/30 hover:text-white">Kembali</a>
                    <button type="submit" class="inline-flex items-center rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-card transition hover:-translate-y-0.5 hover:bg-brand-700">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </main>
</x-layouts.app>
