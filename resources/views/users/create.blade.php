<x-layouts.app :title="'Tambah User | SIPADU'">
    <main class="px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-5xl">
            <div class="mb-6">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-brand-300">User Management</p>
                <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-white">Tambah user</h1>
            </div>

            <form method="POST" action="{{ route('users.store') }}" class="section-panel p-6 sm:p-8">
                @csrf
                @include('users._form', ['userModel' => $userModel])

                <div class="mt-8 flex gap-3">
                    <a href="{{ route('users.index') }}" class="inline-flex items-center rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-200 shadow-sm transition hover:border-brand-400/30 hover:text-white">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-card transition hover:-translate-y-0.5 hover:bg-brand-700">
                        Simpan user
                    </button>
                </div>
            </form>
        </div>
    </main>
</x-layouts.app>
