<x-layouts.app :title="'Kelola User | SIPADU'">
    <main class="px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-brand-300">User Management</p>
                    <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-white">Kelola user SIPADU</h1>
                    <p class="mt-2 text-sm text-slate-400">Atur data identitas, divisi, jabatan, serta tipe kantor pengguna portal.</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('dashboard.index') }}" class="inline-flex items-center rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-200 shadow-sm transition hover:border-brand-400/30 hover:text-white">
                        Dashboard
                    </a>
                    <a href="{{ route('portal.index') }}" class="inline-flex items-center rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-200 shadow-sm transition hover:border-brand-400/30 hover:text-white">
                        Kembali ke portal
                    </a>
                    <a href="{{ route('users.create') }}" class="inline-flex items-center rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-card transition hover:-translate-y-0.5 hover:bg-brand-700">
                        Tambah user
                    </a>
                </div>
            </div>

            @if (session('status'))
                <div class="mb-5 rounded-2xl border border-brand-400/20 bg-brand-400/10 px-4 py-3 text-sm text-brand-100">
                    {{ session('status') }}
                </div>
            @endif

            <form method="GET" action="{{ route('users.index') }}" class="section-panel mb-5 rounded-[24px] p-4 shadow-sm">
                <div class="flex flex-col gap-3 sm:flex-row">
                    <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama, username, ID karyawan, email, divisi, jabatan..." class="block w-full rounded-2xl border border-white/10 bg-[#09120e] px-4 py-3 text-sm text-white shadow-sm placeholder:text-slate-500 focus:border-brand-500 focus:ring-brand-500/30">
                    <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-brand-600 px-5 py-3 text-sm font-semibold text-white shadow-card transition hover:bg-brand-700">
                        Cari
                    </button>
                </div>
            </form>

            <div class="section-panel overflow-hidden rounded-[28px] shadow-soft">
                <div class="overflow-x-auto">
                    <table class="admin-table min-w-full divide-y divide-white/6">
                        <thead>
                            <tr class="text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                                <th class="px-5 py-4">User</th>
                                <th class="px-5 py-4">Divisi & Jabatan</th>
                                <th class="px-5 py-4">Lokasi</th>
                                <th class="px-5 py-4">Kontak</th>
                                <th class="px-5 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/6">
                            @foreach ($users as $user)
                                <tr class="text-sm text-slate-300">
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-white">{{ $user->name }}</p>
                                        <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                                            <span>{{ $user->username }} • {{ $user->employee_id }}</span>
                                            @if ($user->isAdmin())
                                                <span class="rounded-full bg-brand-400/10 px-2 py-1 font-semibold text-brand-200">Admin</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <p>{{ $user->division_name ?: '-' }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $user->title ?: '-' }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <p>{{ $user->office_type === 'branch' ? 'Cabang' : 'Kantor Pusat' }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $user->branch_code ?: '-' }} {{ $user->branch_name ?: '' }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <p>{{ $user->email }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $user->phone ?: '-' }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-xs font-semibold text-slate-200 transition hover:border-brand-400/30 hover:text-white">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5">
                {{ $users->links() }}
            </div>
        </div>
    </main>
</x-layouts.app>
