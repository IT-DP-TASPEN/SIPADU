<x-layouts.app :title="'Detail Log SSO | SIPADU'">
    <main class="px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-6xl">
            <div class="mb-6 flex items-end justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-brand-300">Integration Detail</p>
                    <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-white">Detail log SSO</h1>
                    <p class="mt-2 text-sm text-slate-400">Gunakan halaman ini untuk memastikan payload dan konfigurasi launch SIPADU sudah benar saat integrasi aplikasi.</p>
                </div>
                <a href="{{ route('sso-logs.index') }}" class="inline-flex items-center rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-200 transition hover:border-brand-400/30 hover:text-white">
                    Kembali ke log
                </a>
            </div>

            <div class="grid gap-6 lg:grid-cols-[0.95fr_1.05fr]">
                <section class="section-panel rounded-[28px] p-6">
                    <h2 class="text-xl font-bold text-white">Ringkasan event</h2>
                    <div class="mt-5 space-y-4 text-sm text-slate-300">
                        <div>
                            <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Waktu launch</p>
                            <p class="mt-1 font-semibold text-white">{{ $log->launched_at?->format('d M Y H:i:s') }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.18em] text-slate-500">User</p>
                            <p class="mt-1 font-semibold text-white">{{ $log->user?->name ?? 'User dihapus' }}</p>
                            <p class="mt-1 text-slate-500">{{ $log->user?->employee_id ?? '-' }} · {{ $log->user?->email ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Aplikasi</p>
                            <p class="mt-1 font-semibold text-white">{{ $log->application_name_snapshot ?: $log->application?->name ?: 'Aplikasi dihapus' }}</p>
                            <p class="mt-1 text-slate-500">{{ $log->application_slug_snapshot ?: $log->application?->slug ?: '-' }} · {{ $log->launch_mode_snapshot === 'sso' ? 'SSO' : 'Launch only' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Target URL</p>
                            <p class="mt-1 break-all text-slate-300">{{ $log->target_url }}</p>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Token ID</p>
                                <p class="mt-1 break-all text-slate-300">{{ $log->token_id ?: '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Expired at</p>
                                <p class="mt-1 text-slate-300">{{ $log->token_expires_at?->format('d M Y H:i:s') ?: '-' }}</p>
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Issuer snapshot</p>
                                <p class="mt-1 break-all text-slate-300">{{ $log->issuer_snapshot ?: data_get($log->payload_snapshot, 'iss', '-') }}</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Audience snapshot</p>
                                <p class="mt-1 break-all text-slate-300">{{ $log->audience_snapshot ?: data_get($log->payload_snapshot, 'aud', '-') }}</p>
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">IP Address</p>
                                <p class="mt-1 text-slate-300">{{ $log->ip_address ?: '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">User Agent</p>
                                <p class="mt-1 break-all text-slate-300">{{ $log->user_agent ?: '-' }}</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="section-panel rounded-[28px] p-6">
                    <h2 class="text-xl font-bold text-white">Payload snapshot</h2>
                    <p class="mt-2 text-sm text-slate-400">Data berikut adalah snapshot payload yang dikirim SIPADU saat event launch ini terjadi.</p>

                    @if ($log->payload_snapshot)
                        <div class="mt-5 grid gap-4 md:grid-cols-2">
                            <div>
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Issuer</p>
                                <p class="mt-1 break-all text-sm text-white">{{ $log->issuer_snapshot ?: data_get($log->payload_snapshot, 'iss', '-') }}</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Audience</p>
                                <p class="mt-1 break-all text-sm text-white">{{ $log->audience_snapshot ?: data_get($log->payload_snapshot, 'aud', '-') }}</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Employee ID</p>
                                <p class="mt-1 text-sm text-white">{{ data_get($log->payload_snapshot, 'user.employee_id', '-') }}</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Username</p>
                                <p class="mt-1 text-sm text-white">{{ data_get($log->payload_snapshot, 'user.username', '-') }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Nama</p>
                                <p class="mt-1 text-sm text-white">{{ data_get($log->payload_snapshot, 'user.name', '-') }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Email</p>
                                <p class="mt-1 text-sm text-white">{{ data_get($log->payload_snapshot, 'user.email', '-') }}</p>
                            </div>
                        </div>

                        <div class="mt-5">
                            <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Raw payload</p>
                            <pre class="mt-3 overflow-x-auto rounded-[20px] border border-white/8 bg-[#09120e] p-4 text-xs leading-6 text-slate-200">{{ json_encode($log->payload_snapshot, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                        </div>
                    @else
                        <div class="mt-5 rounded-[20px] border border-dashed border-white/10 px-4 py-8 text-sm text-slate-500">
                            Event ini tidak membawa payload SSO. Biasanya terjadi pada aplikasi mode <code>launch_only</code>.
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </main>
</x-layouts.app>
