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

        $payload = json_decode(base64_decode($encodedPayload), true);

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
            <table>
                <thead>
                    <tr>
                        <th>Aplikasi</th>
                        <th>SSO Login URL</th>
                        <th>Audience</th>
                        <th>Shared Secret</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($applications as $application)
                        <tr>
                            <td>
                                <strong>{{ $application->name }}</strong><br>
                                <span class="small">{{ $application->slug }}</span>
                            </td>
                            <td><code>{{ $application->sso_login_url }}</code></td>
                            <td><code>{{ $application->sso_audience }}</code></td>
                            <td><code>{{ $application->sso_shared_secret }}</code></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">Belum ada aplikasi mode SSO yang aktif.</td>
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
        </section>
    </div>
</body>
</html>
