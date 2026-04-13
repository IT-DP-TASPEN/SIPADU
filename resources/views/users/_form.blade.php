<div class="grid gap-5 md:grid-cols-2">
    <div>
        <x-forms.label for="name">Nama karyawan</x-forms.label>
        <x-forms.input id="name" name="name" type="text" value="{{ old('name', $userModel->name) }}" required />
    </div>

    <div>
        <x-forms.label for="employee_id">ID karyawan</x-forms.label>
        <x-forms.input id="employee_id" name="employee_id" type="text" value="{{ old('employee_id', $userModel->employee_id) }}" required />
    </div>

    <div>
        <x-forms.label for="username">Username</x-forms.label>
        <x-forms.input id="username" name="username" type="text" value="{{ old('username', $userModel->username) }}" required />
    </div>

    <div>
        <x-forms.label for="email">Email</x-forms.label>
        <x-forms.input id="email" name="email" type="email" value="{{ old('email', $userModel->email) }}" required />
    </div>

    <div>
        <x-forms.label for="phone">Nomor HP</x-forms.label>
        <x-forms.input id="phone" name="phone" type="text" value="{{ old('phone', $userModel->phone) }}" />
    </div>

    <div>
        <x-forms.label for="password">Password</x-forms.label>
        <x-forms.input id="password" name="password" type="password" />
        @if(isset($passwordOptional) && $passwordOptional)
            <p class="mt-2 text-xs text-slate-500">Biarkan kosong jika tidak ingin mengganti password.</p>
        @endif
    </div>

    <div>
        <x-forms.label for="division_name">Divisi</x-forms.label>
        <x-forms.input id="division_name" name="division_name" type="text" value="{{ old('division_name', $userModel->division_name) }}" />
    </div>

    <div>
        <x-forms.label for="title">Jabatan</x-forms.label>
        <x-forms.input id="title" name="title" type="text" value="{{ old('title', $userModel->title) }}" />
    </div>

    <div>
        <x-forms.label for="unit_name">Unit</x-forms.label>
        <x-forms.input id="unit_name" name="unit_name" type="text" value="{{ old('unit_name', $userModel->unit_name) }}" />
    </div>

    <div>
        <x-forms.label for="office_type">Tipe kantor</x-forms.label>
        <x-forms.select id="office_type" name="office_type">
            <option value="head_office" @selected(old('office_type', $userModel->office_type) === 'head_office')>Kantor Pusat</option>
            <option value="branch" @selected(old('office_type', $userModel->office_type) === 'branch')>Cabang</option>
        </x-forms.select>
    </div>

    <div>
        <x-forms.label for="branch_code">Kode cabang</x-forms.label>
        <x-forms.input id="branch_code" name="branch_code" type="text" value="{{ old('branch_code', $userModel->branch_code) }}" />
    </div>

    <div>
        <x-forms.label for="branch_name">Nama cabang</x-forms.label>
        <x-forms.input id="branch_name" name="branch_name" type="text" value="{{ old('branch_name', $userModel->branch_name) }}" />
    </div>
</div>

@if ($canManageAdminFlag ?? false)
    <div class="mt-6 flex flex-wrap gap-6">
        <label class="flex items-center gap-3 text-sm text-slate-300">
            <x-forms.checkbox name="is_admin" value="1" @checked(old('is_admin', $userModel->is_admin ?? false)) />
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
    <div class="mt-6 rounded-2xl border border-rose-900/50 bg-rose-900/20 px-4 py-3 text-sm text-rose-300 backdrop-blur-sm shadow-inner">
        Data user belum lengkap atau belum valid. Silakan cek form kembali.
    </div>
@endif
