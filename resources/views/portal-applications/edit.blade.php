<x-layouts.app :title="'Edit Aplikasi | SIPADU'">
    <main class="px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-6xl">
            <!-- Header Section -->
            <div class="relative mb-10 overflow-hidden rounded-[40px] border border-white/10 bg-gradient-to-br from-white/10 to-transparent p-8 backdrop-blur-md">
                <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-brand-500/10 blur-[100px]"></div>
                <div class="absolute -bottom-20 -left-20 h-64 w-64 rounded-full bg-cyan-500/10 blur-[100px]"></div>
                
                <div class="relative flex flex-col items-center gap-8 md:flex-row">
                    <!-- Icon Area -->
                    <div class="relative">
                        <div class="hologram-bracket tl"></div>
                        <div class="hologram-bracket tr"></div>
                        <div class="hologram-bracket bl"></div>
                        <div class="hologram-bracket br"></div>
                        
                        <div class="flex h-32 w-32 items-center justify-center rounded-3xl bg-brand-500/20 text-4xl text-brand-300 shadow-[0_0_40px_rgba(40,147,83,0.3)] outline outline-1 outline-brand-500/30">
                            @include('portal.partials.app-icon', ['icon' => $application->icon])
                        </div>
                    </div>

                    <div class="flex-1 text-center md:text-left">
                        <p class="text-xs font-bold uppercase tracking-[0.3em] text-brand-400">Application Configuration</p>
                        <h1 class="mt-2 text-4xl font-black tracking-tight text-white md:text-5xl text-glow-cyan">{{ $application->name }}</h1>
                        <div class="mt-3 flex flex-wrap justify-center gap-3 md:justify-start">
                            <span class="rounded-full bg-white/5 px-4 py-1.5 text-xs font-semibold text-slate-300 outline outline-1 outline-white/10">
                                {{ $application->badge ?? 'No Badge' }}
                            </span>
                            <span class="rounded-full bg-brand-500/10 px-4 py-1.5 text-xs font-semibold text-brand-300 outline outline-1 outline-brand-500/20">
                                SSO: {{ $application->launch_mode === 'sso' ? 'Enabled' : 'Disabled' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <a href="{{ route('portal-applications.index') }}" class="group flex h-14 w-14 items-center justify-center rounded-2xl border border-white/10 bg-white/5 transition hover:bg-white/10">
                            <svg class="h-6 w-6 text-slate-400 transition group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('portal-applications.update', $application) }}">
                @csrf
                @method('PUT')
                
                @include('portal-applications._form', [
                    'application' => $application, 
                    'divisions' => $divisions, 
                    'titles' => $titles,
                    'existingRules' => $existingRules
                ])

                <!-- Footer Actions -->
                <div class="mt-10 flex flex-col-reverse gap-4 sm:flex-row sm:justify-end">
                    <a href="{{ route('portal-applications.index') }}" class="inline-flex h-14 items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-8 text-sm font-bold text-slate-300 transition hover:bg-white/10 hover:text-white">
                        Batalkan Perubahan
                    </a>
                    <button type="submit" class="group relative inline-flex h-14 items-center justify-center overflow-hidden rounded-2xl bg-brand-600 px-10 text-sm font-bold text-white transition hover:bg-brand-500 shadow-[0_0_20px_rgba(40,147,83,0.3)]">
                        <span class="relative z-10">Simpan Perubahan Aplikasi</span>
                        <div class="absolute inset-0 z-0 bg-gradient-to-r from-transparent via-white/10 to-transparent opacity-0 transition-opacity group-hover:opacity-100" 
                             style="transform: skewX(-20deg); width: 200%; left: -100%; transition: left 0.6s ease-in-out;"></div>
                    </button>
                </div>
            </form>
        </div>
    </main>
</x-layouts.app>
