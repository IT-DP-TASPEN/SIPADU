<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use App\Rules\PasswordComplexity;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    public function __construct(
        protected AuditService $audit,
    ) {}

    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'login' => ['required', 'string'],
        ]);

        $login = $request->input('login');
        $user = User::query()
            ->where('email', $login)
            ->orWhere('username', $login)
            ->orWhere('employee_id', $login)
            ->first();

        if (! $user) {
            return back()->withErrors(['login' => 'Data tidak ditemukan. Pastikan User ID atau Email yang Anda masukkan benar.']);
        }

        if (! $user->isActive()) {
            return back()->withErrors(['login' => 'Akun Anda tidak aktif. Hubungi Administrator.']);
        }

        // Generate token
        $token = Str::random(64);

        // Store in password_reset_tokens table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        // Send email
        try {
            $user->notify(new ResetPasswordNotification($token));
        } catch (\Throwable $e) {
            report($e);
        }

        $this->audit->log('Forgot Password Requested', $user, 'Reset password link sent via email', $request);

        return redirect()->route('forgot-password.sent')->with('email', $user->email);
    }

    public function sent(): View
    {
        return view('auth.forgot-password-sent');
    }

    public function resetForm(Request $request, string $token): View
    {
        // Validate token exists and is not expired
        $ttl = config('uam.forgot_password_token_ttl', 15);

        $record = DB::table('password_reset_tokens')
            ->where('created_at', '>=', now()->subMinutes($ttl))
            ->first();

        if (! $record) {
            return view('auth.forgot-password')
                ->withErrors(['login' => 'Token reset password tidak valid atau sudah kedaluwarsa. Silakan ajukan kembali.']);
        }

        // Find the record with matching token
        $records = DB::table('password_reset_tokens')
            ->where('created_at', '>=', now()->subMinutes($ttl))
            ->get();

        $found = null;
        foreach ($records as $rec) {
            if (Hash::check($token, $rec->token)) {
                $found = $rec;
                break;
            }
        }

        if (! $found) {
            return view('auth.forgot-password')
                ->withErrors(['login' => 'Token reset password tidak valid. Silakan ajukan kembali.']);
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $found->email,
        ]);
    }

    public function reset(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'confirmed'],
        ]);

        $ttl = config('uam.forgot_password_token_ttl', 15);

        $records = DB::table('password_reset_tokens')
            ->where('email', $validated['email'])
            ->where('created_at', '>=', now()->subMinutes($ttl))
            ->get();

        $found = null;
        foreach ($records as $rec) {
            if (Hash::check($validated['token'], $rec->token)) {
                $found = $rec;
                break;
            }
        }

        if (! $found) {
            return back()->withErrors(['email' => 'Token tidak valid atau sudah kedaluwarsa.']);
        }

        $user = User::query()->where('email', $validated['email'])->first();

        if (! $user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        // Validate password complexity + history
        $passwordRule = new PasswordComplexity;
        $request->validate([
            'password' => ['required', 'string', 'confirmed', $passwordRule],
        ], [], [
            'user_id' => $user->id,
            'current_password_hash' => $user->getRawOriginal('password'),
        ]);

        // Update password
        $oldHash = $user->getRawOriginal('password');

        $user->forceFill([
            'password' => Hash::make($validated['password']),
            'password_changed_at' => now(),
            'must_change_password' => false,
        ])->save();

        // Save to history
        $user->passwordHistories()->create([
            'password' => $oldHash,
            'created_at' => now(),
        ]);

        // Delete used token
        DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();

        $this->audit->log('Password Reset via Forgot Password', $user, 'Password changed through forgot password flow', $request);

        return redirect()->route('login')->with('status', 'Password berhasil diubah. Silakan login menggunakan password baru Anda.');
    }
}
