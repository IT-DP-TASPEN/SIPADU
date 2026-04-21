<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checklist Implementasi SSO SIPADU</title>
    <style>
        :root { --green-900:#10261a; --green-700:#1d5f39; --green-500:#2b8a52; --green-100:#e8f4ec; --text:#1d2d24; --muted:#637369; --line:#dbe7df; --bg:#f7faf8; --card:#ffffff; }
        * { box-sizing:border-box; }
        body { margin:0; background:var(--bg); color:var(--text); font-family:"Segoe UI", Arial, sans-serif; font-size:14px; line-height:1.6; }
        .page { max-width:1020px; margin:0 auto; padding:32px 36px 48px; }
        .hero { background:linear-gradient(135deg,var(--green-900),var(--green-700)); color:white; padding:28px 30px; border-radius:22px; margin-bottom:22px; }
        .eyebrow { display:inline-block; padding:6px 10px; border-radius:999px; border:1px solid rgba(255,255,255,0.18); font-size:11px; letter-spacing:.18em; text-transform:uppercase; font-weight:700; }
        h1 { margin:14px 0 10px; font-size:30px; line-height:1.15; }
        h2 { margin:0 0 10px; color:var(--green-700); font-size:20px; }
        h3 { margin:0 0 8px; color:var(--green-700); font-size:16px; }
        .section { background:var(--card); border:1px solid var(--line); border-radius:18px; padding:20px 22px; margin-bottom:16px; }
        .grid { display:grid; grid-template-columns:repeat(2,1fr); gap:14px; margin-top:12px; }
        .task { border:1px solid var(--line); border-radius:16px; padding:14px 16px; background:#fcfefd; }
        ul { margin:8px 0 0 18px; padding:0; }
        li { margin-bottom:6px; }
        .todo { list-style:none; margin:10px 0 0; padding:0; }
        .todo li { display:flex; gap:10px; margin-bottom:9px; }
        .box { width:16px; height:16px; border:1.6px solid var(--green-500); border-radius:4px; margin-top:2px; flex:0 0 16px; }
        .note { margin-top:12px; padding:12px 14px; border-radius:14px; background:var(--green-100); border:1px solid #cfe3d6; }
        code, pre { font-family:Consolas, "Courier New", monospace; }
        pre { margin:10px 0 0; padding:13px 14px; border-radius:14px; background:#101915; color:#eafff0; white-space:pre-wrap; word-break:break-word; }
        table { width:100%; border-collapse:collapse; margin-top:12px; }
        th, td { border:1px solid var(--line); padding:10px 12px; text-align:left; vertical-align:top; }
        th { background:#eef6f1; color:var(--green-700); }
        .small { color:var(--muted); font-size:12px; }
        .copy-field {
            width:100%;
            border:1px solid var(--line);
            border-radius:12px;
            background:#f8fbf9;
            color:var(--text);
            padding:10px 12px;
            font-family:Consolas, "Courier New", monospace;
            font-size:12px;
            line-height:1.5;
            resize:vertical;
        }
        .copy-button {
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:6px;
            border:1px solid #cfe3d6;
            background:#f8fbf9;
            color:var(--green-700);
            border-radius:10px;
            padding:8px 12px;
            font-size:12px;
            font-weight:700;
            cursor:pointer;
            white-space:nowrap;
        }
        .copy-button:hover {
            background:var(--green-100);
        }
        .stack-field {
            display:grid;
            gap:12px;
            min-width:320px;
        }
        .config-block {
            border:1px solid var(--line);
            border-radius:14px;
            background:#fcfefd;
            padding:12px;
        }
        .config-head {
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:10px;
            margin-bottom:8px;
        }
        .config-label {
            font-size:11px;
            font-weight:700;
            letter-spacing:.08em;
            text-transform:uppercase;
            color:var(--muted);
        }
        .link-icon {
            display:inline-flex;
            align-items:center;
            justify-content:center;
            width:38px;
            height:38px;
            border:1px solid var(--line);
            border-radius:10px;
            background:#f8fbf9;
            color:var(--green-700);
            text-decoration:none;
        }
        .link-icon:hover {
            background:var(--green-100);
        }
    </style>
</head>
<body>
    <div class="page">
        <section class="hero">
            <div class="eyebrow">Quick Execution Guide</div>
            <h1>Checklist Implementasi SSO SIPADU untuk Programmer</h1>
            <p>Versi ini dinamis. Konfigurasi aplikasi SSO di bawah selalu mengikuti data terbaru dari SIPADU, termasuk audience, endpoint, dan shared secret.</p>
            <p class="small">Khusus admin • Jangan dibagikan ke publik karena memuat shared secret aplikasi</p>
        </section>

        <section class="section">
            <h2>1. Ringkasan Task</h2>
            <ul class="todo">
                <li><span class="box"></span><span>Tambah kolom <code>employee_id</code> di tabel <code>users</code>.</span></li>
                <li><span class="box"></span><span>Tambahkan config <code>SSO_ENABLED</code>, <code>SSO_ISSUER</code>, <code>SSO_AUDIENCE</code>, dan <code>SSO_SHARED_SECRET</code>.</span></li>
                <li><span class="box"></span><span>Buat route <code>/sso/login</code>.</span></li>
                <li><span class="box"></span><span>Buat service validasi token.</span></li>
                <li><span class="box"></span><span>Buat controller auto-login berdasarkan <code>employee_id</code>.</span></li>
                <li><span class="box"></span><span>Tambahkan replay protection dan audit log.</span></li>
            </ul>
        </section>

        <section class="section">
            <h2>2. Step 4. Service Validasi Token</h2>
            <p>Buat service ini untuk memvalidasi signature, issuer, audience, dan expiry token.</p>
<pre>&lt;?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class SsoTokenService
{
    public function decode(string $token): array
    {
        [$encodedPayload, $signature] = explode('.', $token, 2);

        $expectedSignature = hash_hmac(
            'sha256',
            $encodedPayload,
            config('services.sso.shared_secret')
        );

        if (! hash_equals($expectedSignature, $signature)) {
            abort(403, 'Signature token tidak valid.');
        }

        $decodedPayloadJson = base64_decode(
            strtr($encodedPayload, '-_', '+/').str_repeat('=', (4 - strlen($encodedPayload) % 4) % 4)
        );
        $payload = json_decode($decodedPayloadJson, true);

        if (! is_array($payload)) {
            abort(403, 'Payload token tidak valid.');
        }

        if (Arr::get($payload, 'iss') !== config('services.sso.issuer')) {
            abort(403, 'Issuer token tidak valid.');
        }

        if (Arr::get($payload, 'aud') !== config('services.sso.audience')) {
            abort(403, 'Audience token tidak valid.');
        }

        if (Carbon::createFromTimestamp(Arr::get($payload, 'exp'))->isPast()) {
            abort(403, 'Token sudah expired.');
        }

        return $payload;
    }
}</pre>
        </section>

        <section class="section">
            <h2>3. Step 5. Controller Login SSO</h2>
            <p>Buat controller ini untuk menerima token dari SIPADU, mencari user lokal, lalu login-kan user.</p>
<pre>&lt;?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\SsoTokenService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SsoLoginController extends Controller
{
    public function store(Request $request, SsoTokenService $ssoTokenService): RedirectResponse
    {
        abort_unless(config('services.sso.enabled'), 404);

        $token = (string) $request->query('sso_token');
        abort_if($token === '', 403, 'Token SSO tidak ditemukan.');

        $payload = $ssoTokenService->decode($token);
        $employeeId = data_get($payload, 'user.employee_id');

        $user = User::query()
            ->where('employee_id', $employeeId)
            ->where('is_active', true)
            ->first();

        abort_if(! $user, 403, 'User tidak terdaftar atau tidak aktif.');

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }
}</pre>
            <div class="note">
                <strong>Catatan Filament:</strong> jika aplikasi memakai Filament, ganti redirect akhir menjadi URL panel. Contoh: <code>return redirect()->intended('/admin');</code>
            </div>
        </section>

        <section class="section">
            <h2>4. Config Tiap Aplikasi di SIPADU</h2>
            <p>Gunakan data berikut untuk dicocokkan ke <code>.env</code> aplikasi surrounding. Tabel ini otomatis mengikuti konfigurasi aplikasi SSO yang aktif di SIPADU.</p>
            <div class="note">
                <strong>Catatan shared secret:</strong> nilai pada kolom <code>Shared Secret</code> adalah secret key aplikasi yang harus dicopy ke <code>SSO_SHARED_SECRET</code> di aplikasi surrounding. Jika secret di-regenerate di SIPADU, programmer wajib update nilai yang sama di aplikasi tujuan.
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Aplikasi</th>
                        <th>Login URL</th>
                        <th>Konfigurasi SSO</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($applications as $application)
                        <tr>
                            <td>
                                <strong>{{ $application->name }}</strong><br>
                                <span class="small">{{ $application->slug }}</span>
                            </td>
                            <td>
                                <a href="{{ $application->sso_login_url }}" target="_blank" rel="noreferrer" class="link-icon" title="Buka login URL">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5h5v5" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14 19 5" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14v4a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h4" />
                                    </svg>
                                </a>
                            </td>
                            <td>
                                <div class="stack-field">
                                    <div class="config-block">
                                        <div class="config-head">
                                            <span class="config-label">Audience</span>
                                            <button type="button" class="copy-button" onclick="copySecret('audience-{{ $application->id }}', this)">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <rect x="9" y="9" width="10" height="10" rx="2"></rect>
                                                    <path d="M5 15V5a2 2 0 0 1 2-2h10"></path>
                                                </svg>
                                                Copy
                                            </button>
                                        </div>
                                        <textarea readonly class="copy-field" rows="2" id="audience-{{ $application->id }}">{{ $application->sso_audience }}</textarea>
                                    </div>
                                    <div class="config-block">
                                        <div class="config-head">
                                            <span class="config-label">Shared Secret</span>
                                            <button type="button" class="copy-button" onclick="copySecret('secret-{{ $application->id }}', this)">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <rect x="9" y="9" width="10" height="10" rx="2"></rect>
                                                    <path d="M5 15V5a2 2 0 0 1 2-2h10"></path>
                                                </svg>
                                                Copy
                                            </button>
                                        </div>
                                        <textarea readonly class="copy-field" rows="3" id="secret-{{ $application->id }}">{{ $application->sso_shared_secret }}</textarea>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">Belum ada aplikasi mode SSO yang aktif.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <section class="section">
            <h2>5. Contoh .env per Aplikasi</h2>
            <p>Untuk setiap aplikasi, isi konfigurasi berikut dengan nilai yang sesuai dari tabel di atas.</p>
@if ($applications->isNotEmpty())
<pre>SSO_ENABLED=true
SSO_ISSUER={{ config('app.url') }}
SSO_AUDIENCE={{ $applications->first()->sso_audience }}
SSO_SHARED_SECRET={{ $applications->first()->sso_shared_secret }}</pre>
@else
<pre>SSO_ENABLED=true
SSO_ISSUER={{ config('app.url') }}
SSO_AUDIENCE=nama-aplikasi
SSO_SHARED_SECRET=shared-secret-aplikasi</pre>
@endif
            <div class="note">
                <strong>Letak secret key aplikasi:</strong> programmer dapat melihat secret key terbaru di panduan ini atau di halaman <code>Kelola aplikasi</code> pada field <code>SSO shared secret</code>. Nilai tersebut harus sama persis antara SIPADU dan aplikasi surrounding.
            </div>
        </section>
    </div>
    <script>
        function copySecret(id, button) {
            const field = document.getElementById(id);

            if (!field) {
                return;
            }

            field.select();
            field.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(field.value).then(() => {
                const original = button.textContent;
                button.textContent = 'Copied';
                setTimeout(() => {
                    button.textContent = original;
                }, 1500);
            });
        }
    </script>
</body>
</html>
