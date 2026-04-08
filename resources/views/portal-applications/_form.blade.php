@php
    $keywordValue = old('keywords', isset($application->keywords) ? implode(', ', $application->keywords ?? []) : '');
    $accessRulesValue = old('access_rules', $accessRulesText ?? '');
@endphp

<div class="grid gap-5 md:grid-cols-2">
    <div class="md:col-span-2">
        <label for="name" class="mb-2 block text-sm font-semibold text-ink">Nama aplikasi</label>
        <input id="name" name="name" type="text" value="{{ old('name', $application->name) }}" required class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-ink shadow-sm focus:border-brand-500 focus:ring-brand-500/30">
    </div>

    <div>
        <label for="slug" class="mb-2 block text-sm font-semibold text-ink">Slug</label>
        <input id="slug" name="slug" type="text" value="{{ old('slug', $application->slug) }}" class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-ink shadow-sm focus:border-brand-500 focus:ring-brand-500/30">
    </div>

    <div>
        <label for="badge" class="mb-2 block text-sm font-semibold text-ink">Badge</label>
        <input id="badge" name="badge" type="text" value="{{ old('badge', $application->badge) }}" class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-ink shadow-sm focus:border-brand-500 focus:ring-brand-500/30">
    </div>

    <div>
        <label for="icon" class="mb-2 block text-sm font-semibold text-ink">Icon</label>
        <input id="icon" name="icon" type="text" value="{{ old('icon', $application->icon) }}" placeholder="bank, chart, shield, document" class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-ink shadow-sm focus:border-brand-500 focus:ring-brand-500/30">
    </div>

    <div>
        <label for="accent_color" class="mb-2 block text-sm font-semibold text-ink">Accent color</label>
        <input id="accent_color" name="accent_color" type="text" value="{{ old('accent_color', $application->accent_color) }}" placeholder="brand, emerald, teal, lime, sky" class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-ink shadow-sm focus:border-brand-500 focus:ring-brand-500/30">
    </div>

    <div>
        <label for="launch_mode" class="mb-2 block text-sm font-semibold text-ink">Mode aplikasi</label>
        <select id="launch_mode" name="launch_mode" class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-ink shadow-sm focus:border-brand-500 focus:ring-brand-500/30">
            <option value="sso" @selected(old('launch_mode', $application->launch_mode) === 'sso')>SSO</option>
            <option value="launch_only" @selected(old('launch_mode', $application->launch_mode) === 'launch_only')>Launch only</option>
        </select>
    </div>

    <div>
        <label for="sort_order" class="mb-2 block text-sm font-semibold text-ink">Urutan</label>
        <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $application->sort_order ?? 0) }}" class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-ink shadow-sm focus:border-brand-500 focus:ring-brand-500/30">
    </div>

    <div class="md:col-span-2">
        <label for="description" class="mb-2 block text-sm font-semibold text-ink">Deskripsi</label>
        <textarea id="description" name="description" rows="3" class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-ink shadow-sm focus:border-brand-500 focus:ring-brand-500/30">{{ old('description', $application->description) }}</textarea>
    </div>

    <div class="md:col-span-2">
        <label for="url" class="mb-2 block text-sm font-semibold text-ink">URL aplikasi</label>
        <input id="url" name="url" type="url" value="{{ old('url', $application->url) }}" required class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-ink shadow-sm focus:border-brand-500 focus:ring-brand-500/30">
    </div>

    <div class="md:col-span-2">
        <label for="sso_login_url" class="mb-2 block text-sm font-semibold text-ink">SSO login URL</label>
        <input id="sso_login_url" name="sso_login_url" type="url" value="{{ old('sso_login_url', $application->sso_login_url) }}" class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-ink shadow-sm focus:border-brand-500 focus:ring-brand-500/30">
    </div>

    <div>
        <label for="sso_audience" class="mb-2 block text-sm font-semibold text-ink">SSO audience</label>
        <input id="sso_audience" name="sso_audience" type="text" value="{{ old('sso_audience', $application->sso_audience) }}" class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-ink shadow-sm focus:border-brand-500 focus:ring-brand-500/30">
    </div>

    <div>
        <label for="sso_shared_secret" class="mb-2 block text-sm font-semibold text-ink">SSO shared secret</label>
        <input id="sso_shared_secret" name="sso_shared_secret" type="text" value="{{ old('sso_shared_secret', $application->sso_shared_secret) }}" class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-ink shadow-sm focus:border-brand-500 focus:ring-brand-500/30">
        <p class="mt-2 text-xs leading-5 text-slate-500">
            Kosongkan saat membuat aplikasi baru untuk generate otomatis. Gunakan regenerate jika ingin membuat secret baru.
        </p>
        <label class="mt-3 flex items-center gap-3 text-sm text-slate-600">
            <input type="checkbox" name="regenerate_sso_shared_secret" value="1" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500/30">
            Generate ulang shared secret
        </label>
    </div>

    <div class="md:col-span-2">
        <label for="keywords" class="mb-2 block text-sm font-semibold text-ink">Keywords</label>
        <input id="keywords" name="keywords" type="text" value="{{ $keywordValue }}" placeholder="pisahkan dengan koma" class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-ink shadow-sm focus:border-brand-500 focus:ring-brand-500/30">
    </div>

    <div class="md:col-span-2">
        <label for="access_rules" class="mb-2 block text-sm font-semibold text-ink">Aturan akses</label>
        <textarea id="access_rules" name="access_rules" rows="6" class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-ink shadow-sm focus:border-brand-500 focus:ring-brand-500/30">{{ $accessRulesValue }}</textarea>
        <p class="mt-2 text-xs leading-5 text-slate-500">
            Satu baris = satu rule. Format: <code>divisi | jabatan | office_type | branch_code | branch_name</code>.
            Gunakan <code>*</code> untuk nilai bebas. Contoh: <code>IT | IT Development Staff | head_office | * | *</code>
        </p>
    </div>
</div>

<div class="mt-6 flex flex-wrap gap-6">
    <label class="flex items-center gap-3 text-sm text-slate-600">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $application->is_active ?? true)) class="rounded border-slate-300 text-brand-600 focus:ring-brand-500/30">
        Aktif
    </label>
    <label class="flex items-center gap-3 text-sm text-slate-600">
        <input type="checkbox" name="is_frequent" value="1" @checked(old('is_frequent', $application->is_frequent ?? false)) class="rounded border-slate-300 text-brand-600 focus:ring-brand-500/30">
        Frequent app
    </label>
    <label class="flex items-center gap-3 text-sm text-slate-600">
        <input type="checkbox" name="open_in_new_tab" value="1" @checked(old('open_in_new_tab', $application->open_in_new_tab ?? true)) class="rounded border-slate-300 text-brand-600 focus:ring-brand-500/30">
        Buka di tab baru
    </label>
</div>

@if ($errors->any())
    <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
        Data belum lengkap atau belum valid. Silakan cek form kembali.
    </div>
@endif
