<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
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
            'employee_id' => ['required', 'string', 'max:255', Rule::unique('users', 'employee_id')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'unit_name' => ['nullable', 'string', 'max:255'],
            'division_name' => ['nullable', 'string', 'max:255'],
            'office_type' => ['required', Rule::in(['head_office', 'branch'])],
            'branch_code' => ['nullable', 'string', 'max:255'],
            'branch_name' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $passwordChanged = ! empty($data['password']);

        if (! $passwordChanged) {
            unset($data['password']);
        }
        // else: let the model's 'hashed' cast auto-hash the password

        $user->update($data);

        if ($passwordChanged) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('status', 'Password berhasil diperbarui. Silakan login kembali menggunakan password baru Anda.');
        }

        return redirect()
            ->route('profile.edit')
            ->with('status', 'Profil berhasil diperbarui.');
    }
}
