<div class="grid gap-8 lg:grid-cols-3">
    <!-- Left Column: Primary Config -->
    <div class="space-y-8 lg:col-span-2">
        <!-- Identity Card -->
        <div class="glass-morphism rounded-[32px] p-8">
            <div class="mb-6 flex items-center gap-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-500/20 text-cyan-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white">Identitas Aplikasi</h3>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <x-forms.label for="name">Nama Aplikasi</x-forms.label>
                    <x-forms.input id="name" name="name" type="text" value="{{ old('name', $application->name) }}" required placeholder="Contoh: Portal HRD" />
                </div>
                <div>
                    <x-forms.label for="slug">URL Slug (Auto)</x-forms.label>
                    <x-forms.input id="slug" name="slug" type="text" value="{{ old('slug', $application->slug) }}" placeholder="portal-hrd" />
                </div>
                <div>
                    <x-forms.label for="badge">Label Badge</x-forms.label>
                    <x-forms.input id="badge" name="badge" type="text" value="{{ old('badge', $application->badge) }}" placeholder="New, HR, etc." />
                </div>
                <div class="sm:col-span-2">
                    <x-forms.label for="description">Deskripsi Singkat</x-forms.label>
                    <x-forms.textarea id="description" name="description" rows="2" placeholder="Jelaskan fungsi utama aplikasi ini...">{{ old('description', $application->description) }}</x-forms.textarea>
                </div>
            </div>
        </div>

        <!-- SSO & Integration Card -->
        <div class="section-panel rounded-[32px] p-8">
            <div class="mb-6 flex items-center gap-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/20 text-amber-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white">SSO & Integrasi</h3>
            </div>

            <div class="grid gap-6">
                <div class="grid gap-6 sm:grid-cols-2">
                    <div>
                        <x-forms.label for="launch_mode">Mode Integrasi</x-forms.label>
                        <x-forms.select id="launch_mode" name="launch_mode">
                            <option value="sso" @selected(old('launch_mode', $application->launch_mode) === 'sso')>Portal SSO (Secure)</option>
                            <option value="launch_only" @selected(old('launch_mode', $application->launch_mode) === 'launch_only')>Direct Launch Only</option>
                        </x-forms.select>
                    </div>
                </div>

                <div>
                    <x-forms.label for="url">URL Utama Aplikasi</x-forms.label>
                    <x-forms.input id="url" name="url" type="url" value="{{ old('url', $application->url) }}" required placeholder="https://..." />
                </div>

                <div class="grid gap-6 sm:grid-cols-2">
                    <div>
                        <x-forms.label for="sso_login_url">SSO Checkpoint URL</x-forms.label>
                        <x-forms.input id="sso_login_url" name="sso_login_url" type="url" value="{{ old('sso_login_url', $application->sso_login_url) }}" placeholder="https://..." />
                    </div>
                    <div>
                        <x-forms.label for="sso_audience">SSO Audience ID</x-forms.label>
                        <x-forms.input id="sso_audience" name="sso_audience" type="text" value="{{ old('sso_audience', $application->sso_audience) }}" placeholder="app-id" />
                    </div>
                </div>

                <div class="rounded-2xl bg-white/5 p-6 border border-white/5">
                    <x-forms.label for="sso_shared_secret">Shared Secret Key</x-forms.label>
                    <div class="flex gap-3">
                        <div class="flex-1">
                            <x-forms.input id="sso_shared_secret" name="sso_shared_secret" type="text" value="{{ old('sso_shared_secret', $application->sso_shared_secret) }}" placeholder="Secret Token" />
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="flex items-center gap-3 text-sm text-slate-300">
                            <x-forms.checkbox name="regenerate_sso_shared_secret" value="1" />
                            <span>Generasi ulang Secret Key saat penyimpanan ini</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Presentation & Compliance -->
    <div class="space-y-8">
        <!-- Visual Presentation Card -->
        <div class="glass-morphism rounded-[32px] p-8">
            <div class="mb-6 flex items-center gap-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-500/20 text-purple-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white">Gaya & Visual</h3>
            </div>

            <div class="space-y-6">
                <div>
                    <x-forms.label for="icon">Icon Aplikasi</x-forms.label>
                    <x-forms.select id="icon" name="icon">
                        @foreach(['bank', 'chart', 'shield', 'users', 'support', 'box', 'book', 'document'] as $iconItem)
                            <option value="{{ $iconItem }}" @selected(old('icon', $application->icon) === $iconItem)>
                                {{ ucfirst($iconItem) }} Icon
                            </option>
                        @endforeach
                    </x-forms.select>
                </div>

                <div>
                    <x-forms.label for="accent_color">Warna Aksen</x-forms.label>
                    <x-forms.select id="accent_color" name="accent_color">
                        @foreach(['brand', 'emerald', 'teal', 'lime', 'sky', 'slate', 'amber', 'rose', 'orange', 'violet'] as $colorItem)
                            <option value="{{ $colorItem }}" @selected(old('accent_color', $application->accent_color) === $colorItem)>
                                {{ ucfirst($colorItem) }}
                            </option>
                        @endforeach
                    </x-forms.select>
                </div>

                <div>
                    <x-forms.label for="sort_order">Urutan Tampilan</x-forms.label>
                    <x-forms.input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $application->sort_order ?? 0) }}" />
                </div>
            </div>
        </div>

        <!-- Access Control Card -->
        <div class="glass-morphism rounded-[32px] p-8 border-brand-500/20 shadow-[0_0_50px_rgba(40,147,83,0.05)]">
            <div class="mb-6 flex items-center gap-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-500/20 text-brand-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white">Akses & Izin</h3>
            </div>

            <div class="space-y-6">
                <div>
                    <x-forms.label for="access_rules">Pilih Divisi & Jabatan</x-forms.label>
                    <p class="mb-3 text-[10px] uppercase tracking-widest text-slate-500 font-bold">Multi Choice Dropdown</p>
                    <div class="relative group">
                        <select id="access_rules" name="access_rules[]" multiple size="8" class="block w-full rounded-2xl border border-white/10 bg-white/5 px-2 py-2 text-sm text-slate-100 shadow-inner backdrop-blur-sm focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/30 transition-colors custom-scrollbar">
                            <optgroup label="Berdasarkan Divisi" class="text-xs font-bold text-brand-300 bg-[#0f1f18] py-2">
                                @foreach($divisions as $div)
                                    <option value="{{ $div }}" @selected(collect(old('access_rules', $existingRules ?? []))->contains($div)) class="py-2 px-3 hover:bg-white/10">{{ $div }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Berdasarkan Jabatan" class="text-xs font-bold text-amber-500 bg-[#1a1a1a] py-2">
                                @foreach($titles as $title)
                                    <option value="{{ $title }}" @selected(collect(old('access_rules', $existingRules ?? []))->contains($title)) class="py-2 px-3 hover:bg-white/10">{{ $title }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                        <p class="mt-3 text-[11px] leading-relaxed text-slate-400 italic">
                            Tahan <kbd class="px-1 rounded bg-white/10 border border-white/10">Ctrl</kbd> atau <kbd class="px-1 rounded bg-white/10 border border-white/10">Cmd</kbd> untuk memilih lebih dari satu.
                        </p>
                    </div>
                </div>

                <div class="pt-4 space-y-4">
                    <label class="flex items-center gap-3 text-sm font-semibold text-slate-300">
                        <x-forms.checkbox name="is_active" value="1" @checked(old('is_active', $application->is_active ?? true)) />
                        Status Aktif
                    </label>
                    <label class="flex items-center gap-3 text-sm font-semibold text-slate-300">
                        <x-forms.checkbox name="is_frequent" value="1" @checked(old('is_frequent', $application->is_frequent ?? false)) />
                        Aplikasi Utama (Frequent)
                    </label>
                    <label class="flex items-center gap-3 text-sm font-semibold text-slate-300">
                        <x-forms.checkbox name="open_in_new_tab" value="1" @checked(old('open_in_new_tab', $application->open_in_new_tab ?? true)) />
                        Buka di Tab Baru
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 8px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.02);
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(40, 147, 83, 0.3);
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(40, 147, 83, 0.5);
    }
    select[multiple] option:checked {
        background: linear-gradient(to right, rgba(40, 147, 83, 0.4), rgba(40, 147, 83, 0.2)) !important;
        color: white !important;
    }
</style>
