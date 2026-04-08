<x-layouts.app :title="'Profil Saya | SIPADU'">
    <main class="px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-5xl">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-brand-300">My Profile</p>
                    <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-white">Profil Saya</h1>
                    <p class="mt-2 text-sm text-slate-400">Perbarui informasi akun yang tampil dan digunakan di SIPADU.</p>
                </div>
                <a href="{{ route('portal.index') }}" class="inline-flex items-center rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-200 shadow-sm transition hover:border-brand-400/30 hover:text-white">
                    Kembali ke portal
                </a>
            </div>

            @if (session('status'))
                <div class="mb-5 rounded-2xl border border-brand-400/20 bg-brand-400/10 px-4 py-3 text-sm text-brand-100">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" class="section-panel rounded-[28px] p-6 shadow-soft sm:p-8">
                @csrf
                @method('PUT')
                @include('users._form', ['userModel' => $userModel, 'passwordOptional' => true, 'canManageAdminFlag' => false])

                <div class="mt-8 flex gap-3">
                    <a href="{{ route('portal.index') }}" class="inline-flex items-center rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-200 shadow-sm transition hover:border-brand-400/30 hover:text-white">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-card transition hover:-translate-y-0.5 hover:bg-brand-700">
                        Simpan profil
                    </button>
                </div>
            </form>
        </div>
    </main>
</x-layouts.app>
