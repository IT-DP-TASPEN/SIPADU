<x-layouts.app :title="'Lupa Password | SIPADU'">
    <main class="flex min-h-screen items-center justify-center px-4 py-10 sm:px-6">
        <div class="w-full max-w-lg">
            <div class="section-panel rounded-[32px] p-8">
                <div class="mb-8 text-center">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-brand-500/20">
                        <svg class="h-8 w-8 text-brand-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white">Lupa Password</h2>
                    <p class="mt-2 text-sm text-slate-400">Masukkan User ID, Username, atau Email terdaftar. Kami akan mengirim tautan reset password.</p>
                </div>

                <form method="POST" action="{{ route('forgot-password.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <x-forms.label for="login" class="text-slate-200">User ID / Username / Email</x-forms.label>
                        <x-forms.input id="login" name="login" type="text" value="{{ old('login') }}" required autofocus />
                        @error('login')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-brand-600 px-5 py-3 text-sm font-semibold text-white shadow-card transition hover:-translate-y-0.5 hover:bg-brand-700">
                        Kirim Tautan Reset
                    </button>
                </form>

                <div class="mt-5 text-center">
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-brand-300 transition hover:text-brand-200">
                        Kembali ke halaman login
                    </a>
                </div>
            </div>
        </div>
    </main>
</x-layouts.app>
