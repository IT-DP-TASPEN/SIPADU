<x-layouts.app :title="'Ganti Password Wajib | SIPADU'">
    <main class="flex min-h-screen items-center justify-center px-4 py-10 sm:px-6">
        <div class="w-full max-w-lg">
            <div class="section-panel rounded-[32px] border border-amber-500/20 p-8">
                <div class="mb-8 text-center">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-amber-500/20">
                        <svg class="h-8 w-8 text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white">Ganti Password Wajib</h2>
                    <p class="mt-2 text-sm text-slate-400">
                        @if($user->must_change_password)
                            Anda wajib mengganti password sebelum dapat mengakses sistem.
                        @else
                            Password Anda telah kedaluwarsa. Silakan buat password baru.
                        @endif
                    </p>
                </div>

                @if(session('warning'))
                    <div class="mb-5 rounded-2xl border border-amber-500/30 bg-amber-500/10 px-4 py-3 text-sm text-amber-200">
                        {{ session('warning') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('force-change-password.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <x-forms.label for="password" class="text-slate-200">Password Baru</x-forms.label>
                        <x-forms.input id="password" name="password" type="password" required autofocus />
                        @error('password')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-forms.label for="password_confirmation" class="text-slate-200">Konfirmasi Password Baru</x-forms.label>
                        <x-forms.input id="password_confirmation" name="password_confirmation" type="password" required />
                    </div>

                    <div class="rounded-2xl bg-white/5 p-4 text-xs text-slate-400">
                        <p class="font-semibold text-slate-300 mb-2">Kebijakan Password:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Minimal 8 karakter</li>
                            <li>Mengandung huruf besar (A-Z)</li>
                            <li>Mengandung huruf kecil (a-z)</li>
                            <li>Mengandung angka (0-9)</li>
                            <li>Mengandung karakter khusus (@, #, $, !, dll.)</li>
                            <li>Tidak boleh sama dengan 5 password terakhir</li>
                        </ul>
                    </div>

                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-brand-600 px-5 py-3 text-sm font-semibold text-white shadow-card transition hover:-translate-y-0.5 hover:bg-brand-700">
                        Simpan Password Baru
                    </button>
                </form>

                <div class="mt-5 text-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-slate-500 hover:text-slate-300 transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</x-layouts.app>
