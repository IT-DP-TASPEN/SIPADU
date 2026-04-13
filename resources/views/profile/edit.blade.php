<x-layouts.app :title="'Profil Saya | SIPADU'">
    @php
        $initials = collect(explode(' ', $userModel->name))->map(fn($name) => mb_substr($name, 0, 1))->take(2)->join('');
    @endphp

    <main class="px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-6xl">
            <!-- Header Section -->
            <div class="relative mb-10 overflow-hidden rounded-[40px] border border-white/10 bg-gradient-to-br from-white/10 to-transparent p-8 backdrop-blur-md">
                <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-brand-500/10 blur-[100px]"></div>
                <div class="absolute -bottom-20 -left-20 h-64 w-64 rounded-full bg-cyan-500/10 blur-[100px]"></div>
                
                <div class="relative flex flex-col items-center gap-8 md:flex-row">
                    <!-- Avatar Area -->
                    <div class="relative">
                        <div class="hologram-bracket tl"></div>
                        <div class="hologram-bracket tr"></div>
                        <div class="hologram-bracket bl"></div>
                        <div class="hologram-bracket br"></div>
                        
                        <div class="flex h-32 w-32 items-center justify-center rounded-3xl bg-brand-500/20 text-4xl font-black text-brand-300 shadow-[0_0_40px_rgba(40,147,83,0.3)] outline outline-1 outline-brand-500/30">
                            {{ $initials }}
                        </div>
                        
                        <div class="absolute -bottom-2 -right-2 flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500 shadow-[0_0_15px_rgba(16,185,129,0.5)]">
                            <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>

                    <div class="flex-1 text-center md:text-left">
                        <p class="text-xs font-bold uppercase tracking-[0.3em] text-brand-400">Cyber Account Profile</p>
                        <h1 class="mt-2 text-4xl font-black tracking-tight text-white md:text-5xl text-glow-cyan">{{ $userModel->name }}</h1>
                        <div class="mt-3 flex flex-wrap justify-center gap-3 md:justify-start">
                            <span class="rounded-full bg-white/5 px-4 py-1.5 text-xs font-semibold text-slate-300 outline outline-1 outline-white/10">
                                {{ $userModel->title ?? 'Professional User' }}
                            </span>
                            <span class="rounded-full bg-brand-500/10 px-4 py-1.5 text-xs font-semibold text-brand-300 outline outline-1 outline-brand-500/20">
                                ID: {{ $userModel->employee_id }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <a href="{{ route('portal.index') }}" class="group flex h-14 w-14 items-center justify-center rounded-2xl border border-white/10 bg-white/5 transition hover:bg-white/10">
                            <svg class="h-6 w-6 text-slate-400 transition group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="grid gap-8 lg:grid-cols-3">
                    <!-- Left Column: Primary Data -->
                    <div class="space-y-8 lg:col-span-2">
                        <!-- Identity Card -->
                        <div class="glass-morphism rounded-[32px] p-8">
                            <div class="mb-6 flex items-center gap-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-500/20 text-cyan-400">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-white">Informasi Identitas</h3>
                            </div>

                            <div class="grid gap-6 sm:grid-cols-2">
                                <div>
                                    <x-forms.label for="name">Nama Lengkap</x-forms.label>
                                    <x-forms.input id="name" name="name" type="text" value="{{ old('name', $userModel->name) }}" required />
                                </div>
                                <div>
                                    <x-forms.label for="username">Username Sistem</x-forms.label>
                                    <x-forms.input id="username" name="username" type="text" value="{{ old('username', $userModel->username) }}" required />
                                </div>
                                <div class="sm:col-span-2">
                                    <x-forms.label for="email">Alamat Email Resmi</x-forms.label>
                                    <x-forms.input id="email" name="email" type="email" value="{{ old('email', $userModel->email) }}" required />
                                </div>
                            </div>
                        </div>

                        <!-- Security & Contact Card -->
                        <div class="section-panel rounded-[32px] p-8">
                            <div class="mb-6 flex items-center gap-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/20 text-amber-400">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-white">Privasi & Keamanan</h3>
                            </div>

                            <div class="grid gap-6 sm:grid-cols-2">
                                <div>
                                    <x-forms.label for="phone">Nomor Telepon / WA</x-forms.label>
                                    <x-forms.input id="phone" name="phone" type="text" value="{{ old('phone', $userModel->phone) }}" placeholder="08..." />
                                </div>
                                <div>
                                    <x-forms.label for="password">Ganti Password</x-forms.label>
                                    <x-forms.input id="password" name="password" type="password" placeholder="••••••••" />
                                    <p class="mt-2 text-xs text-slate-400 italic">Biarkan kosong jika tidak ingin mengganti.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Institutional (Read Only) -->
                    <div class="space-y-8">
                        <div class="glass-morphism h-full rounded-[32px] border-brand-500/20 p-8 shadow-[0_0_50px_rgba(40,147,83,0.05)]">
                            <div class="mb-8 flex items-center gap-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-500/20 text-brand-400">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-white">Afiliasi Kantor</h3>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <label class="mb-1 block text-xs font-bold uppercase tracking-widest text-slate-500">Divisi / Departemen</label>
                                    <p class="text-lg font-medium text-slate-200">{{ $userModel->division_name ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-bold uppercase tracking-widest text-slate-500">Unit Kerja</label>
                                    <p class="text-lg font-medium text-slate-200">{{ $userModel->unit_name ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-bold uppercase tracking-widest text-slate-500">Lokasi Penempatan</label>
                                    <p class="text-lg font-medium text-slate-200">
                                        {{ $userModel->branch_name }} 
                                        <span class="ml-1 text-sm text-brand-400">({{ $userModel->branch_code }})</span>
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $userModel->office_type === 'head_office' ? 'Kantor Pusat' : 'Kantor Cabang' }}</p>
                                </div>
                            </div>

                            <div class="mt-12 rounded-2xl bg-white/5 p-4 text-center border border-white/5">
                                <p class="text-xs text-slate-500">Informasi di atas dikelola oleh Administrator. Hubungi IT jika ada ketidaksesuaian.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="mt-10 flex flex-col-reverse gap-4 sm:flex-row sm:justify-end">
                    <a href="{{ route('portal.index') }}" class="inline-flex h-14 items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-8 text-sm font-bold text-slate-300 transition hover:bg-white/10 hover:text-white">
                        Batalkan Perubahan
                    </a>
                    <button type="submit" class="group relative inline-flex h-14 items-center justify-center overflow-hidden rounded-2xl bg-brand-600 px-10 text-sm font-bold text-white transition hover:bg-brand-500 shadow-[0_0_20px_rgba(40,147,83,0.3)]">
                        <span class="relative z-10">Simpan Profil Saya</span>
                        <div class="absolute inset-0 z-0 bg-gradient-to-r from-transparent via-white/10 to-transparent opacity-0 transition-opacity group-hover:opacity-100" 
                             style="transform: skewX(-20deg); width: 200%; left: -100%; transition: left 0.6s ease-in-out;"></div>
                    </button>
                </div>
            </form>
        </div>
    </main>
</x-layouts.app>

