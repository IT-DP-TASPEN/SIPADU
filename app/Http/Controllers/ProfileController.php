<?php

namespace App\Http\Controllers;

use App\Rules\PasswordComplexity;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        protected AuditService $audit,
    ) {}

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'userModel' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:255'],
            'password' => [
                'nullable',
                'string',
                'confirmed',
                new PasswordComplexity([
                    'user_id' => $user->id,
                    'current_password_hash' => $user->getRawOriginal('password'),
                ]),
            ],
        ]);

        // Fields managed by admin (read-only in profile form)
        $extraFields = ['employee_id', 'title', 'unit_name', 'division_name', 'office_type', 'branch_code', 'branch_name'];
        foreach ($extraFields as $field) {
            if ($request->has($field)) {
                $data[$field] = $request->input($field);
            }
        }

        $passwordChanged = ! empty($data['password']);

        if ($passwordChanged) {
            $oldHash = $user->getRawOriginal('password');
            $data['password'] = Hash::make($data['password']);
            $data['password_changed_at'] = now();

            // Save old password to history
            $user->passwordHistories()->create([
                'password' => $oldHash,
                'created_at' => now(),
            ]);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        if ($passwordChanged) {
            $this->audit->log('Password Changed', $user, 'Password changed via profile');

            $user->uamNotifications()->create([
                'type' => 'security',
                'title' => 'Password Berhasil Diubah',
                'body' => 'Password Anda telah berhasil diperbarui.',
                'priority' => 'medium',
                'created_at' => now(),
            ]);

            return redirect()
                ->route('profile.edit')
                ->with('warning', 'Password berhasil diperbarui. Silakan klik tombol OK untuk logout dan login kembali menggunakan password baru Anda.')
                ->with('password_changed', true);
        }

        return redirect()
            ->route('profile.edit')
            ->with('status', 'Profil berhasil diperbarui.');
    }
}
