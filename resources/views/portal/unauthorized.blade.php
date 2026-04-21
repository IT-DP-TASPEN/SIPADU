<x-layouts.app :title="'Akses Ditolak - ' . $application->name">
    <main class="flex min-h-[80vh] flex-col items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="glass-panel mx-auto max-w-lg rounded-[32px] border border-white/10 p-8 text-center shadow-2xl relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute -top-24 -right-24 h-48 w-48 rounded-full bg-rose-500/20 blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 h-48 w-48 rounded-full bg-rose-500/20 blur-3xl"></div>
            
            <!-- Icon -->
            <div class="relative mx-auto flex h-24 w-24 items-center justify-center rounded-[24px] bg-gradient-to-br from-rose-500/20 to-rose-600/20 border border-rose-500/30 mb-8 shadow-[0_0_40px_rgba(244,63,94,0.3)]">
                <div class="hologram-bracket tl !border-rose-500/50"></div>
                <div class="hologram-bracket tr !border-rose-500/50"></div>
                <div class="hologram-bracket bl !border-rose-500/50"></div>
                <div class="hologram-bracket br !border-rose-500/50"></div>
                <svg class="h-10 w-10 text-rose-400 drop-shadow-[0_0_10px_rgba(244,63,94,0.8)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <!-- Content -->
            <h1 class="mb-4 text-2xl font-extrabold text-white tracking-tight sm:text-3xl">Akses Tidak Diizinkan</h1>
            <div class="mb-8 space-y-4 text-slate-300">
                <p>
                    Mohon maaf, Anda tidak memiliki akses ke aplikasi <strong class="text-white">{{ $application->name }}</strong> berdasarkan profil divisi dan jabatan Anda saat ini.
                </p>
                <div class="rounded-2xl border border-white/5 bg-black/20 p-4 text-sm">
                    <p class="text-slate-400">
                        Jika Anda memang membutuhkan akses ini untuk keperluan operasional, silakan hubungi tim IT atau input tiket ke Aplikasi Helpdesk.
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('portal.index') }}" class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-xl bg-white/10 px-6 py-3.5 text-sm font-semibold text-white transition-all hover:bg-white/20 hover:shadow-lg border border-white/10">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Portal
                </a>
                
                {{-- Uncomment or link to actual helpdesk --}}
                {{-- <a href="#" class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-500 to-cyan-500 px-6 py-3.5 text-sm font-semibold text-white transition-all hover:scale-105 hover:shadow-[0_0_20px_rgba(40,147,83,0.4)]">
                    <svg class="h-4 w-4 text-white/90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Buka Helpdesk
                </a> --}}
            </div>
        </div>
    </main>
</x-layouts.app>
