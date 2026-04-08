<x-layouts.app :title="'Edit User | SIPADU'">
    <main class="px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-5xl">
            <div class="mb-6">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-brand-700">User Management</p>
                <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-ink">Edit user</h1>
                <p class="mt-2 text-sm text-slate-600">{{ $userModel->name }}</p>
            </div>

            <form method="POST" action="{{ route('users.update', $userModel) }}" class="rounded-[28px] border border-white/80 bg-white/85 p-6 shadow-soft sm:p-8">
                @csrf
                @method('PUT')
                @include('users._form', ['userModel' => $userModel, 'passwordOptional' => true])

                <div class="mt-8 flex gap-3">
                    <a href="{{ route('users.index') }}" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-brand-200 hover:text-brand-700">
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
