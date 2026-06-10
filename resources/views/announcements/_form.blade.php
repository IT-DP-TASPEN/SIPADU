<div class="grid gap-5 md:grid-cols-2">
    <div class="md:col-span-2">
        <x-forms.label for="title">Judul Pengumuman</x-forms.label>
        <x-forms.input id="title" name="title" type="text" value="{{ old('title', $announcement->title) }}" required />
    </div>

    <div class="md:col-span-2">
        <x-forms.label for="body">Isi Pengumuman</x-forms.label>
        <textarea id="body" name="body" rows="6" required class="block w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 shadow-inner backdrop-blur-sm placeholder:text-slate-500 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/30 transition-colors">{{ old('body', $announcement->body) }}</textarea>
    </div>

    <div>
        <x-forms.label for="priority">Prioritas</x-forms.label>
        <x-forms.select id="priority" name="priority">
            <option value="low" @selected(old('priority', $announcement->priority) === 'low')>Low</option>
            <option value="medium" @selected(old('priority', $announcement->priority ?? 'medium') === 'medium')>Medium</option>
            <option value="high" @selected(old('priority', $announcement->priority) === 'high')>High</option>
        </x-forms.select>
    </div>

    <div>
        <x-forms.label for="status">Status</x-forms.label>
        <x-forms.select id="status" name="status">
            <option value="draft" @selected(old('status', $announcement->status ?? 'draft') === 'draft')>Draft</option>
            <option value="published" @selected(old('status', $announcement->status) === 'published')>Published</option>
            <option value="archived" @selected(old('status', $announcement->status) === 'archived')>Archived</option>
        </x-forms.select>
    </div>

    <div>
        <x-forms.label for="publish_at">Tanggal Mulai Tayang</x-forms.label>
        <x-forms.input id="publish_at" name="publish_at" type="datetime-local" value="{{ old('publish_at', $announcement->publish_at?->format('Y-m-d\TH:i')) }}" />
    </div>

    <div>
        <x-forms.label for="expire_at">Tanggal Berakhir Tayang</x-forms.label>
        <x-forms.input id="expire_at" name="expire_at" type="datetime-local" value="{{ old('expire_at', $announcement->expire_at?->format('Y-m-d\TH:i')) }}" />
    </div>

    <div>
        <x-forms.label for="target_type">Target Penerima</x-forms.label>
        <x-forms.select id="target_type" name="target_type">
            <option value="all" @selected(old('target_type', $announcement->target_type ?? 'all') === 'all')>Semua User</option>
            <option value="unit" @selected(old('target_type', $announcement->target_type) === 'unit')>Unit Kerja</option>
            <option value="branch" @selected(old('target_type', $announcement->target_type) === 'branch')>Cabang</option>
            <option value="user" @selected(old('target_type', $announcement->target_type) === 'user')>User Tertentu</option>
        </x-forms.select>
    </div>

    <div>
        <x-forms.label for="target_value">Nilai Target</x-forms.label>
        <x-forms.input id="target_value" name="target_value" type="text" value="{{ old('target_value', $announcement->target_value) }}" placeholder="Kosongkan jika target = semua" />
    </div>
</div>

@if($errors->any())
    <div class="mt-6 rounded-2xl border border-rose-900/50 bg-rose-900/20 px-4 py-3 text-sm text-rose-300">
        Data pengumuman belum lengkap atau belum valid.
    </div>
@endif
