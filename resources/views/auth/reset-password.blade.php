<x-layouts.app :title="'Reset Password | SIPADU'">
    <main class="flex min-h-screen items-center justify-center px-4 py-10 sm:px-6">
        <div class="w-full max-w-lg">
            <div class="section-panel rounded-[32px] p-8">
                <div class="mb-8 text-center">
                    <h2 class="text-2xl font-bold text-white">Buat Password Baru</h2>
                    <p class="mt-2 text-sm text-slate-400">Password minimal 8 karakter, mengandung huruf besar, huruf kecil, angka, dan karakter khusus.</p>
                </div>

                <form method="POST" action="{{ route('password.reset.store') }}" class="space-y-5">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div>
                        <x-forms.label for="password" class="text-slate-200">Password Baru</x-forms.label>
                        <x-forms.input id="password" name="password" type="password" required autofocus />
                        @error('password')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-forms.label for="password_confirmation" class="text-slate-200">Konfirmasi Password</x-forms.label>
                        <x-forms.input id="password_confirmation" name="password_confirmation" type="password" required />
                    </div>

                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-brand-600 px-5 py-3 text-sm font-semibold text-white shadow-card transition hover:-translate-y-0.5 hover:bg-brand-700">
                        Reset Password
                    </button>
                </form>
            </div>
        </div>
    </main>
</x-layouts.app>
