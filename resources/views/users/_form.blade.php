<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label for="name" class="mb-2 block text-sm font-semibold text-ink">Nama karyawan</label>
        <input id="name" name="name" type="text" value="{{ old('name', $userModel->name) }}" required class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-ink shadow-sm focus:border-brand-500 focus:ring-brand-500/30">
    </div>

    <div>
        <label for="employee_id" class="mb-2 block text-sm font-semibold text-ink">ID karyawan</label>
        <input id="employee_id" name="employee_id" type="text" value="{{ old('employee_id', $userModel->employee_id) }}" required class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-ink shadow-sm focus:border-brand-500 focus:ring-brand-500/30">
    </div>

    <div>
        <label for="username" class="mb-2 block text-sm font-semibold text-ink">Username</label>
        <input id="username" name="username" type="text" value="{{ old('username', $userModel->username) }}" required class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-ink shadow-sm focus:border-brand-500 focus:ring-brand-500/30">
    </div>

    <div>
        <label for="email" class="mb-2 block text-sm font-semibold text-ink">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $userModel->email) }}" required class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-ink shadow-sm focus:border-brand-500 focus:ring-brand-500/30">
    </div>

    <div>
        <label for="phone" class="mb-2 block text-sm font-semibold text-ink">Nomor HP</label>
        <input id="phone" name="phone" type="text" value="{{ old('phone', $userModel->phone) }}" class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-ink shadow-sm focus:border-brand-500 focus:ring-brand-500/30">
    </div>

    <div>
        <label for="password" class="mb-2 block text-sm font-semibold text-ink">Password</label>
        <input id="password" name="password" type="password" class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-ink shadow-sm focus:border-brand-500 focus:ring-brand-500/30">
        @if(isset($passwordOptional) && $passwordOptional)
            <p class="mt-2 text-xs text-slate-500">Biarkan kosong jika tidak ingin mengganti password.</p>
        @endif
    </div>

    <div>
        <label for="division_name" class="mb-2 block text-sm font-semibold text-ink">Divisi</label>
        <input id="division_name" name="division_name" type="text" value="{{ old('division_name', $userModel->division_name) }}" class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-ink shadow-sm focus:border-brand-500 focus:ring-brand-500/30">
    </div>

    <div>
        <label for="title" class="mb-2 block text-sm font-semibold text-ink">Jabatan</label>
        <input id="title" name="title" type="text" value="{{ old('title', $userModel->title) }}" class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-ink shadow-sm focus:border-brand-500 focus:ring-brand-500/30">
    </div>

    <div>
        <label for="unit_name" class="mb-2 block text-sm font-semibold text-ink">Unit</label>
        <input id="unit_name" name="unit_name" type="text" value="{{ old('unit_name', $userModel->unit_name) }}" class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-ink shadow-sm focus:border-brand-500 focus:ring-brand-500/30">
    </div>

    <div>
        <label for="office_type" class="mb-2 block text-sm font-semibold text-ink">Tipe kantor</label>
        <select id="office_type" name="office_type" class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-ink shadow-sm focus:border-brand-500 focus:ring-brand-500/30">
            <option value="head_office" @selected(old('office_type', $userModel->office_type) === 'head_office')>Kantor Pusat</option>
            <option value="branch" @selected(old('office_type', $userModel->office_type) === 'branch')>Cabang</option>
        </select>
    </div>

    <div>
        <label for="branch_code" class="mb-2 block text-sm font-semibold text-ink">Kode cabang</label>
        <input id="branch_code" name="branch_code" type="text" value="{{ old('branch_code', $userModel->branch_code) }}" class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-ink shadow-sm focus:border-brand-500 focus:ring-brand-500/30">
    </div>

    <div>
        <label for="branch_name" class="mb-2 block text-sm font-semibold text-ink">Nama cabang</label>
        <input id="branch_name" name="branch_name" type="text" value="{{ old('branch_name', $userModel->branch_name) }}" class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-ink shadow-sm focus:border-brand-500 focus:ring-brand-500/30">
    </div>
</div>

@if ($canManageAdminFlag ?? false)
    <div class="mt-6 flex flex-wrap gap-6">
        <label class="flex items-center gap-3 text-sm text-slate-600">
            <input type="checkbox" name="is_admin" value="1" @checked(old('is_admin', $userModel->is_admin ?? false)) class="rounded border-slate-300 text-brand-600 focus:ring-brand-500/30">
            Admin portal
        </label>
    </div>

    @error('is_admin')
        <div class="mt-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            {{ $message }}
        </div>
    @enderror
@endif

@if ($errors->any())
    <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
        Data user belum lengkap atau belum valid. Silakan cek form kembali.
    </div>
@endif
