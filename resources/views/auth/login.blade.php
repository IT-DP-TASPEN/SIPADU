<x-layouts.app :title="'Login | SIPADU'">
    <main class="flex min-h-screen items-center justify-center px-4 py-10 sm:px-6">
        <div class="grid w-full max-w-6xl overflow-hidden rounded-[36px] border border-white/8 bg-[#0d1813]/85 shadow-soft backdrop-blur-xl lg:grid-cols-[1.08fr_0.92fr]">
            <section class="relative hidden overflow-hidden bg-[linear-gradient(180deg,#0f1f18_0%,#0a1411_100%)] px-10 py-12 text-white lg:block">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(78,181,114,0.18),transparent_30%)]"></div>
                <div class="relative">
                    <div class="mb-10">
                        <img src="{{ asset('logo_putih.png') }}" alt="Bank DP Taspen" class="h-28 w-auto object-contain">
                    </div>
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-brand-300">SIPADU</p>
                    <h1 class="mt-4 max-w-xl text-3xl font-extrabold leading-[1.16] tracking-tight text-white">
                        Portal terpusat untuk akses aplikasi internal Bank DP Taspen.
                    </h1>
                    <p class="mt-5 max-w-xl text-base leading-8 text-slate-300">
                        Satu pintu masuk untuk mencari dan membuka aplikasi kerja secara cepat, aman, dan terkontrol.
                    </p>
                    <div class="mt-10 grid gap-4">
                        <div class="rounded-[28px] border border-white/8 bg-white/5 p-5">
                            <p class="text-sm font-semibold text-white">Search-first portal</p>
                            <p class="mt-2 text-sm leading-7 text-slate-400">Cari aplikasi dan fitur kerja lebih cepat dari satu portal internal.</p>
                        </div>
                        <div class="rounded-[28px] border border-white/8 bg-white/5 p-5">
                            <p class="text-sm font-semibold text-white">SSO gateway</p>
                            <p class="mt-2 text-sm leading-7 text-slate-400">Mendukung akses SSO serta aplikasi yang tetap memerlukan login mandiri.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="px-6 py-8 sm:px-10 sm:py-12">
                <div class="mx-auto max-w-md">
                    <div class="mb-8">
                        <img src="{{ asset('logo_putih.png') }}" alt="Bank DP Taspen" class="mb-5 h-20 w-auto object-contain lg:hidden">
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-brand-300">Sistem Informasi Portal Terpadu</p>
                        <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-white">Masuk ke SIPADU</h2>
                        <p class="mt-3 text-sm leading-6 text-slate-400">Gunakan akun portal untuk masuk ke aplikasi internal Bank DP Taspen.</p>
                    </div>

                    <form method="POST" action="{{ route('login.store') }}" class="space-y-5 rounded-[28px] border border-white/8 bg-white/5 p-6 shadow-card">
                        @csrf

                        <div>
                            <x-forms.label for="login" class="text-slate-200">Username atau Email</x-forms.label>
                            <x-forms.input id="login" name="login" type="text" value="{{ old('login') }}" required autofocus />
                            @error('login')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <div class="mb-2 flex items-center justify-between gap-3">
                                <x-forms.label for="password" class="mb-0 text-slate-200">Password</x-forms.label>
                                {{-- <span class="text-xs text-slate-500">Contoh: DPT@SP3n</span> --}}
                            </div>
                            <x-forms.input id="password" name="password" type="password" required />
                        </div>

                        <label class="flex items-center gap-3 text-sm text-slate-400">
                            <x-forms.checkbox name="remember" value="1" />
                            Ingat sesi pada perangkat ini
                        </label>

                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-brand-600 px-5 py-3 text-sm font-semibold text-white shadow-card transition hover:-translate-y-0.5 hover:bg-brand-700">
                            Masuk ke portal
                        </button>
                    </form>

                    <div class="mt-5 space-y-2 text-center text-sm text-slate-500">
                        <p>Versi aplikasi 1.0.0</p>
                        <a href="https://hdms.bankdptaspen.co.id/admin/login" target="_blank" rel="noreferrer" class="inline-flex justify-center font-semibold text-brand-300 transition hover:text-brand-200">
                            Bantuan akses via System Helpdesk
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </main>
</x-layouts.app>
