@php
    $keywordValue = old('keywords', isset($application->keywords) ? implode(', ', $application->keywords ?? []) : '');
    $accessRulesValue = old('access_rules', $accessRulesText ?? '');
@endphp

<div class="grid gap-5 md:grid-cols-2">
    <div class="md:col-span-2">
        <x-forms.label for="name">Nama aplikasi</x-forms.label>
        <x-forms.input id="name" name="name" type="text" value="{{ old('name', $application->name) }}" required />
    </div>

    <div>
        <x-forms.label for="slug">Slug</x-forms.label>
        <x-forms.input id="slug" name="slug" type="text" value="{{ old('slug', $application->slug) }}" />
    </div>

    <div>
        <x-forms.label for="badge">Badge</x-forms.label>
        <x-forms.input id="badge" name="badge" type="text" value="{{ old('badge', $application->badge) }}" />
    </div>

    <div>
        <x-forms.label for="icon">Icon</x-forms.label>
        <x-forms.input id="icon" name="icon" type="text" value="{{ old('icon', $application->icon) }}" placeholder="bank, chart, shield, document" />
    </div>

    <div>
        <x-forms.label for="accent_color">Accent color</x-forms.label>
        <x-forms.input id="accent_color" name="accent_color" type="text" value="{{ old('accent_color', $application->accent_color) }}" placeholder="brand, emerald, teal, lime, sky" />
    </div>

    <div>
        <x-forms.label for="launch_mode">Mode aplikasi</x-forms.label>
        <x-forms.select id="launch_mode" name="launch_mode">
            <option value="sso" @selected(old('launch_mode', $application->launch_mode) === 'sso')>SSO</option>
            <option value="launch_only" @selected(old('launch_mode', $application->launch_mode) === 'launch_only')>Launch only</option>
        </x-forms.select>
    </div>

    <div>
        <x-forms.label for="sort_order">Urutan</x-forms.label>
        <x-forms.input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $application->sort_order ?? 0) }}" />
    </div>

    <div class="md:col-span-2">
        <x-forms.label for="description">Deskripsi</x-forms.label>
        <x-forms.textarea id="description" name="description" rows="3">{{ old('description', $application->description) }}</x-forms.textarea>
    </div>

    <div class="md:col-span-2">
        <x-forms.label for="url">URL aplikasi</x-forms.label>
        <x-forms.input id="url" name="url" type="url" value="{{ old('url', $application->url) }}" required />
    </div>

    <div class="md:col-span-2">
        <x-forms.label for="sso_login_url">SSO login URL</x-forms.label>
        <x-forms.input id="sso_login_url" name="sso_login_url" type="url" value="{{ old('sso_login_url', $application->sso_login_url) }}" />
    </div>

    <div>
        <x-forms.label for="sso_audience">SSO audience</x-forms.label>
        <x-forms.input id="sso_audience" name="sso_audience" type="text" value="{{ old('sso_audience', $application->sso_audience) }}" />
    </div>

    <div>
        <x-forms.label for="sso_shared_secret">SSO shared secret</x-forms.label>
        <x-forms.input id="sso_shared_secret" name="sso_shared_secret" type="text" value="{{ old('sso_shared_secret', $application->sso_shared_secret) }}" />
        <p class="mt-2 text-xs leading-5 text-slate-500">
            Kosongkan saat membuat aplikasi baru untuk generate otomatis. Gunakan regenerate jika ingin membuat secret baru.
        </p>
        <label class="mt-3 flex items-center gap-3 text-sm text-slate-300">
            <x-forms.checkbox name="regenerate_sso_shared_secret" value="1" />
            Generate ulang shared secret
        </label>
    </div>

    <div class="md:col-span-2">
        <x-forms.label for="keywords">Keywords</x-forms.label>
        <x-forms.input id="keywords" name="keywords" type="text" value="{{ $keywordValue }}" placeholder="pisahkan dengan koma" />
    </div>

    <div class="md:col-span-2">
        <x-forms.label for="access_rules">Aturan akses</x-forms.label>
        <x-forms.textarea id="access_rules" name="access_rules" rows="6">{{ $accessRulesValue }}</x-forms.textarea>
        <p class="mt-2 text-xs leading-5 text-slate-500">
            Satu baris = satu rule. Format: <code>divisi | jabatan | office_type | branch_code | branch_name</code>.
            Gunakan <code>*</code> untuk nilai bebas. Contoh: <code>IT | IT Development Staff | head_office | * | *</code>
        </p>
    </div>
</div>

<div class="mt-6 flex flex-wrap gap-6">
    <label class="flex items-center gap-3 text-sm text-slate-300">
        <x-forms.checkbox name="is_active" value="1" @checked(old('is_active', $application->is_active ?? true)) />
        Aktif
    </label>
    <label class="flex items-center gap-3 text-sm text-slate-300">
        <x-forms.checkbox name="is_frequent" value="1" @checked(old('is_frequent', $application->is_frequent ?? false)) />
        Frequent app
    </label>
    <label class="flex items-center gap-3 text-sm text-slate-300">
        <x-forms.checkbox name="open_in_new_tab" value="1" @checked(old('open_in_new_tab', $application->open_in_new_tab ?? true)) />
        Buka di tab baru
    </label>
</div>

@if ($errors->any())
    <div class="mt-6 rounded-2xl border border-rose-900/50 bg-rose-900/20 px-4 py-3 text-sm text-rose-300 backdrop-blur-sm shadow-inner">
        Data belum lengkap atau belum valid. Silakan cek form kembali.
    </div>
@endif

