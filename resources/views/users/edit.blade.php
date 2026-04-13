<x-layouts.app :title="'Edit User | SIPADU'">
    <main class="px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-5xl">
            <div class="mb-6">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-brand-300">User Management</p>
                <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-white">Edit user</h1>
                <p class="mt-2 text-sm text-slate-400">{{ $userModel->name }}</p>
            </div>

            <form method="POST" action="{{ route('users.update', $userModel) }}" class="section-panel p-6 sm:p-8">
                @csrf
                @method('PUT')
                @include('users._form', ['userModel' => $userModel, 'passwordOptional' => true])

                <div class="mt-8 flex gap-3">
                    <a href="{{ route('users.index') }}" class="inline-flex items-center rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-200 shadow-sm transition hover:border-brand-400/30 hover:text-white">
                        Kembali
                    </a>
                    <button type="submit" class="inline-flex items-center rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-card transition hover:-translate-y-0.5 hover:bg-brand-700">
                        Simpan perubahan
                    </button>
                </div>
            </form>
        </div>
    </main>
</x-layouts.app>
