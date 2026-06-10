<x-layouts.app :title="'Email Terkirim | SIPADU'">
    <main class="flex min-h-screen items-center justify-center px-4 py-10 sm:px-6">
        <div class="w-full max-w-lg">
            <div class="section-panel rounded-[32px] p-8 text-center">
                <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-brand-500/20">
                    <svg class="h-10 w-10 text-brand-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                </div>

                <h2 class="text-2xl font-bold text-white">Cek Email Anda</h2>
                <p class="mt-3 text-sm leading-relaxed text-slate-400">
                    Jika akun ditemukan, tautan reset password telah dikirim ke email Anda.
                    Tautan berlaku selama {{ config('uam.forgot_password_token_ttl', 15) }} menit.
                </p>
                @if(session('email'))
                    <p class="mt-2 text-sm text-slate-500">Email: <strong class="text-slate-300">{{ session('email') }}</strong></p>
                @endif

                <p class="mt-6 text-xs text-slate-500">
                    Tidak menerima email? Periksa folder spam, atau
                    <a href="{{ route('forgot-password') }}" class="font-semibold text-brand-300 hover:text-brand-200">ajukan kembali</a>.
                </p>

                <div class="mt-8">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-6 py-3 text-sm font-semibold text-slate-200 transition hover:border-brand-400/30 hover:text-white">
                        Kembali ke Login
                    </a>
                </div>
            </div>
        </div>
    </main>
</x-layouts.app>
