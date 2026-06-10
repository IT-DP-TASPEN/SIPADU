<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        protected AuditService $audit,
    ) {}

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginField = filter_var($validated['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Find user first to check status before auth attempt
        $user = User::query()->where($loginField, $validated['login'])->first();

        if ($user) {
            // Check account expiry in real-time
            if ($user->isActive() && $user->isAccountExpired()) {
                $oldStatus = $user->status;
                $user->forceFill(['status' => User::STATUS_EXPIRED])->save();

                $this->audit->log('Account Expired', $user, "Status changed: {$oldStatus} -> expired (Expired by System)", $request);
            }

            // Check locked status
            if ($user->isLocked()) {
                $this->audit->log('Login Failed (Locked)', $user, 'Attempted login while account locked', $request);

                return back()
                    ->withErrors(['login' => 'Akun Anda terkunci karena terlalu banyak percobaan login gagal. Hubungi Administrator.'])
                    ->onlyInput('login');
            }

            // Check inactive
            if ($user->isInactive()) {
                $this->audit->log('Login Failed (Inactive)', $user, 'Attempted login while account inactive', $request);

                return back()
                    ->withErrors(['login' => 'Akun Anda tidak aktif. Hubungi Administrator.'])
                    ->onlyInput('login');
            }

            // Check expired
            if ($user->isExpired()) {
                $this->audit->log('Login Failed (Expired)', $user, 'Attempted login while account expired', $request);

                return back()
                    ->withErrors(['login' => 'Akun Anda telah kedaluwarsa. Hubungi Administrator untuk memperpanjang.'])
                    ->onlyInput('login');
            }
        }

        // Attempt authentication
        if (! Auth::attempt([$loginField => $validated['login'], 'password' => $validated['password']], $request->boolean('remember'))) {
            // Failed login
            if ($user) {
                $user->increment('login_failure_count');

                $maxAttempts = config('uam.max_login_attempts', 5);

                if ($user->login_failure_count >= $maxAttempts) {
                    $user->forceFill([
                        'status' => User::STATUS_LOCKED,
                        'locked_at' => now(),
                    ])->save();

                    $this->audit->log('Account Locked', $user, "Login gagal {$maxAttempts}x berturut-turut", $request);
                } else {
                    $this->audit->log('Login Failed', $user, 'Invalid credentials', $request);
                }
            }

            $remaining = $user ? max(0, config('uam.max_login_attempts', 5) - $user->fresh()->login_failure_count) : null;
            $message = 'Username/email atau password tidak sesuai.';
            if ($remaining !== null && $remaining > 0) {
                $message .= " Sisa percobaan: {$remaining}.";
            }

            return back()
                ->withErrors(['login' => $message])
                ->onlyInput('login');
        }

        $request->session()->regenerate();

        /** @var User $user */
        $user = Auth::user();

        // Reset failure count on successful login
        $user->forceFill([
            'last_login_at' => now(),
            'login_failure_count' => 0,
            'locked_at' => null,
        ])->save();

        // Single session: invalidate other sessions
        if (config('uam.single_session', true)) {
            DB::table('sessions')
                ->where('user_id', $user->id)
                ->where('id', '!=', $request->session()->getId())
                ->delete();
        }

        $this->audit->log('Login Success', $user, '', $request);

        // Check if must change password
        if ($user->must_change_password) {
            return redirect()->route('force-change-password');
        }

        // Check password expiry
        if ($user->isPasswordExpired()) {
            return redirect()->route('force-change-password')
                ->with('warning', 'Password Anda telah kedaluwarsa. Silakan ganti password Anda.');
        }

        return redirect()->intended(route('portal.index'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user) {
            $this->audit->log('Logout', $user, '', $request);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
