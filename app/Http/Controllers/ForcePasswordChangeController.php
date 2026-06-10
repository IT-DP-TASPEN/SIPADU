<?php

namespace App\Http\Controllers;

use App\Rules\PasswordComplexity;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ForcePasswordChangeController extends Controller
{
    public function __construct(
        protected AuditService $audit,
    ) {}

    public function create(Request $request): View
    {
        return view('auth.force-change-password', [
            'user' => $request->user(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'password' => [
                'required',
                'string',
                'confirmed',
                new PasswordComplexity([
                    'user_id' => $user->id,
                    'current_password_hash' => $user->getRawOriginal('password'),
                ]),
            ],
        ]);

        $oldHash = $user->getRawOriginal('password');

        $user->forceFill([
            'password' => Hash::make($validated['password']),
            'password_changed_at' => now(),
            'must_change_password' => false,
        ])->save();

        // Save old password to history
        $user->passwordHistories()->create([
            'password' => $oldHash,
            'created_at' => now(),
        ]);

        $this->audit->log('Force Password Changed', $user, 'Password changed via force change password', $request);

        // Create security notification
        $user->uamNotifications()->create([
            'type' => 'security',
            'title' => 'Password Berhasil Diubah',
            'body' => 'Password Anda telah berhasil diperbarui.',
            'priority' => 'medium',
            'created_at' => now(),
        ]);

        return redirect()->route('portal.index')
            ->with('status', 'Password berhasil diubah. Anda dapat melanjutkan menggunakan sistem.');
    }
}
