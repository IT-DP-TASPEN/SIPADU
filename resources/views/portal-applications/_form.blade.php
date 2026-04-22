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
                    <x-forms.label for="icon">Preset Icon</x-forms.label>
                    <p class="mb-3 text-[11px] text-slate-500">Pilih salah satu icon standar di bawah ini.</p>
                    <div class="grid grid-cols-4 gap-3" id="icon-picker">
                        @foreach(['bank', 'chart', 'shield', 'users', 'support', 'box', 'book', 'document'] as $iconItem)
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="icon" value="{{ $iconItem }}" class="peer sr-only" @checked(old('icon', $application->icon) === $iconItem)>
                                <div class="flex flex-col items-center justify-center p-3 rounded-xl border border-white/10 bg-white/5 transition peer-checked:border-brand-500 peer-checked:bg-brand-500/20 group-hover:bg-white/10 h-full">
                                    <div class="text-slate-400 peer-checked:text-brand-400 group-hover:text-white transition">
                                        @include('portal.partials.app-icon', ['icon' => $iconItem])
                                    </div>
                                    <span class="mt-2 text-[10px] text-slate-500 uppercase font-bold peer-checked:text-slate-300">{{ $iconItem }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4 pt-6 border-t border-white/5">
                    <x-forms.label for="icon_file">Atau Upload Logo Custom</x-forms.label>
                    <div class="mt-3">
                        <div class="relative group">
                            <input type="file" id="icon_file" name="icon_file" accept="image/png,image/jpeg,image/svg+xml,image/webp" 
                                   class="block w-full text-sm text-slate-400 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-500/20 file:text-brand-400 hover:file:bg-brand-500/30 file:transition-all transition-all">
                        </div>
                        <div class="mt-3 rounded-xl bg-amber-500/5 border border-amber-500/10 p-3">
                            <p class="text-[11px] leading-relaxed text-amber-200/70">
                                <strong class="text-amber-400">Rekomendasi:</strong> Gunakan ukuran <span class="text-white font-bold">512x512px</span> (Rasio 1:1) untuk hasil terbaik. <br>
                                <strong class="text-amber-400">Format:</strong> PNG, JPG, SVG, atau WebP (Maks. 5MB).
                            </p>
                        </div>
                    </div>
                    
                    @if($application->isCustomIcon())
                        <div class="mt-4 flex items-center gap-4 p-4 rounded-2xl bg-brand-500/5 border border-brand-500/20 shadow-lg shadow-brand-500/5">
                            <div class="h-16 w-16 rounded-xl overflow-hidden bg-white/10 flex items-center justify-center p-2 border border-white/10">
                                <img src="{{ '/storage/' . $application->icon }}" alt="Current Logo" class="h-full w-full object-contain">
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-bold text-white uppercase tracking-wider">Logo Custom Aktif</p>
                                <p class="mt-1 text-[10px] text-brand-300 font-medium">Aplikasi ini menggunakan logo yang diupload.</p>
                                <p class="text-[10px] text-slate-500 italic mt-1">Pilih preset icon di atas jika ingin menggantinya kembali.</p>
                            </div>
                        </div>
                    @endif
                </div>

                <div>
                    <x-forms.label for="accent_color">Warna Aksen</x-forms.label>
                    <x-forms.select id="accent_color" name="accent_color">
                        @php
                            $colors = [
                                'brand' => 'Hijau SIPADU',
                                'emerald' => 'Hijau',
                                'teal' => 'Toska',
                                'sky' => 'Biru',
                                'violet' => 'Ungu',
                                'rose' => 'Merah',
                                'amber' => 'Kuning',
                                'orange' => 'Oranye',
                                'slate' => 'Abu-abu',
                            ];
                        @endphp
                        @foreach($colors as $value => $label)
                            <option value="{{ $value }}" @selected(old('accent_color', $application->accent_color) === $value)>
                                {{ $label }}
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
                    <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <p class="text-[10px] uppercase tracking-widest text-slate-500 font-bold">Multi Choice List</p>
                            <div class="flex gap-2 border-l border-white/10 pl-3">
                                <button type="button" id="access_rules_select_all" class="text-[10px] font-bold text-brand-400 hover:text-brand-300 transition uppercase tracking-wider">Pilih Semua</button>
                                <button type="button" id="access_rules_clear_all" class="text-[10px] font-bold text-rose-400 hover:text-rose-300 transition uppercase tracking-wider">Hapus Semua</button>
                            </div>
                        </div>
                        <div class="relative w-full sm:w-1/2">
                            <input type="text" id="access_rules_search" placeholder="Cari divisi/jabatan..." 
                                   class="block w-full rounded-xl border-white/10 bg-white/5 py-1.5 pl-8 pr-3 text-[11px] text-white placeholder-slate-600 focus:border-brand-500 focus:ring-0">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-2.5 text-slate-600">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="relative group">
                        <select id="access_rules" name="access_rules[]" multiple size="8" class="block w-full rounded-2xl border border-white/10 bg-white/5 px-2 py-2 text-sm text-slate-100 shadow-inner backdrop-blur-sm focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/30 transition-colors custom-scrollbar">
                            <optgroup label="Berdasarkan Divisi" id="group_divisions" class="text-xs font-bold text-brand-300 bg-[#0f1f18] py-2">
                                @foreach($divisions as $div)
                                    <option value="{{ $div }}" @selected(collect(old('access_rules', $existingRules ?? []))->contains($div)) class="py-2 px-3 hover:bg-white/10">{{ $div }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Berdasarkan Jabatan" id="group_titles" class="text-xs font-bold text-amber-500 bg-[#1a1a1a] py-2">
                                @foreach($titles as $title)
                                    <option value="{{ $title }}" @selected(collect(old('access_rules', $existingRules ?? []))->contains($title)) class="py-2 px-3 hover:bg-white/10">{{ $title }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                        <p class="mt-3 text-[11px] leading-relaxed text-slate-400 italic">
                            Tahan <kbd class="px-1 rounded bg-white/10 border border-white/10">Ctrl</kbd> atau <kbd class="px-1 rounded bg-white/10 border border-white/10">Cmd</kbd> untuk memilih lebih dari satu.
                        </p>
                    </div>

                    <!-- Selected Items Tags Container -->
                    <div class="mt-4">
                        <p class="mb-2 text-[10px] uppercase tracking-widest text-slate-500 font-bold">Item Terpilih</p>
                        <div id="selected_rules_tags" class="flex flex-wrap gap-2 min-h-[40px] rounded-2xl border border-dashed border-white/10 p-3 bg-white/2 transition-colors">
                            <p id="no_selection_text" class="text-xs text-slate-600 italic">Belum ada item yang dipilih</p>
                    </div>
                </div>
                </div> <!-- Closes selection group (Line 146) -->

                <div class="pt-6 space-y-5 border-t border-white/10 mt-6">
                    <label class="flex items-center gap-4 text-sm font-bold text-slate-200 cursor-pointer group">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $application->is_active ?? true)) 
                               class="w-5 h-5 rounded border-2 border-white/20 bg-brand-900/40 text-brand-500 focus:ring-brand-500/50 focus:ring-offset-0 transition-all cursor-pointer">
                        <span class="group-hover:text-brand-400 transition-colors">Status Aktif</span>
                    </label>
                    
                    <label class="flex items-center gap-4 text-sm font-bold text-slate-200 cursor-pointer group">
                        <input type="checkbox" name="is_frequent" value="1" @checked(old('is_frequent', $application->is_frequent ?? false)) 
                               class="w-5 h-5 rounded border-2 border-white/20 bg-brand-900/40 text-brand-500 focus:ring-brand-500/50 focus:ring-offset-0 transition-all cursor-pointer">
                        <span class="group-hover:text-brand-400 transition-colors">Aplikasi Utama (Frequent)</span>
                    </label>
                    
                    <label class="flex items-center gap-4 text-sm font-bold text-slate-200 cursor-pointer group">
                        <input type="checkbox" name="open_in_new_tab" value="1" @checked(old('open_in_new_tab', $application->open_in_new_tab ?? true)) 
                               class="w-5 h-5 rounded border-2 border-white/20 bg-brand-900/40 text-brand-500 focus:ring-brand-500/50 focus:ring-offset-0 transition-all cursor-pointer">
                        <span class="group-hover:text-brand-400 transition-colors">Buka di Tab Baru</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div> <!-- Closes last grid containers -->

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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('access_rules_search');
        const selectAllBtn = document.getElementById('access_rules_select_all');
        const clearAllBtn = document.getElementById('access_rules_clear_all');
        const select = document.getElementById('access_rules');
        const tagsContainer = document.getElementById('selected_rules_tags');
        const noSelectionText = document.getElementById('no_selection_text');
        const options = Array.from(select.options);

        // --- Tag Rendering Logic ---
        function renderTags() {
            const selectedOptions = options.filter(opt => opt.selected);
            
            // Clear current tags except "no selection" placeholder
            tagsContainer.innerHTML = '';
            
            if (selectedOptions.length === 0) {
                tagsContainer.appendChild(noSelectionText);
                return;
            }

            selectedOptions.forEach(option => {
                const tag = document.createElement('div');
                const isDivision = option.parentElement.id === 'group_divisions';
                const accentClass = isDivision ? 'bg-brand-500/10 text-brand-300 border-brand-500/20' : 'bg-amber-500/10 text-amber-300 border-amber-500/20';
                
                tag.className = `flex items-center gap-2 rounded-lg border ${accentClass} px-2 py-1 text-[11px] font-bold shadow-sm transition animation-fade-in`;
                tag.innerHTML = `
                    <span>${option.text}</span>
                    <button type="button" class="tag-remove text-white/40 hover:text-white transition" data-value="${option.value}">
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                `;
                tagsContainer.appendChild(tag);
            });

            // Re-attach event listeners for remove buttons
            document.querySelectorAll('.tag-remove').forEach(btn => {
                btn.addEventListener('click', function() {
                    const val = this.dataset.value;
                    const opt = options.find(o => o.value === val);
                    if (opt) {
                        opt.selected = false;
                        renderTags();
                    }
                });
            });
        }

        // --- Search Logic ---
        if (searchInput && select) {
            searchInput.addEventListener('input', function(e) {
                const term = e.target.value.toLowerCase();
                
                options.forEach(option => {
                    const text = option.text.toLowerCase();
                    const matches = text.includes(term);
                    
                    if (matches) {
                        option.style.display = 'block';
                    } else {
                        if (!option.selected) {
                            option.style.display = 'none';
                        }
                    }
                });

                document.querySelectorAll('#access_rules optgroup').forEach(group => {
                    const visibleOptions = Array.from(group.options).filter(opt => opt.style.display !== 'none');
                    group.style.display = visibleOptions.length > 0 ? 'block' : 'none';
                });
            });
        }

        // --- Bulk Actions ---
        if (selectAllBtn && select) {
            selectAllBtn.addEventListener('click', function() {
                options.forEach(option => {
                    if (option.style.display !== 'none') {
                        option.selected = true;
                    }
                });
                renderTags();
            });
        }

        if (clearAllBtn && select) {
            clearAllBtn.addEventListener('click', function() {
                options.forEach(option => {
                    option.selected = false;
                });
                renderTags();
            });
        }

        // --- Event Listeners ---
        select.addEventListener('change', renderTags);

        // Initial render
        renderTags();

        // --- Auto-generate SSO Checkpoint URL & Slug ---
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        const urlInput = document.getElementById('url');
        const ssoUrlInput = document.getElementById('sso_login_url');

        if (nameInput && slugInput) {
            let slugManual = slugInput.value !== '';
            
            slugInput.addEventListener('input', function() {
                slugManual = true;
            });
            
            nameInput.addEventListener('input', function() {
                if (!slugManual || slugInput.value === '') {
                    slugInput.value = nameInput.value.toLowerCase()
                        .replace(/[^a-z0-9\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-')
                        .replace(/^-|-$/g, '');
                }
            });
        }

        if (urlInput && ssoUrlInput) {
            let ssoManual = ssoUrlInput.value !== '';
            
            ssoUrlInput.addEventListener('input', function() {
                ssoManual = true;
            });

            urlInput.addEventListener('input', function() {
                if (!ssoManual || ssoUrlInput.value === '') {
                    const baseUrl = urlInput.value.replace(/\/+$/, '');
                    if (baseUrl) {
                        ssoUrlInput.value = baseUrl + '/api/sso/login';
                    } else {
                        ssoUrlInput.value = '';
                    }
                }
            });
        }
    });
</script>

<style>
    .animation-fade-in {
        animation: fadeIn 0.2s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(2px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
