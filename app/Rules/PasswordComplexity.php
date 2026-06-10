<?php

namespace App\Rules;

use App\Models\PasswordHistory;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class PasswordComplexity implements ValidationRule, DataAwareRule
{
    protected array $data = [];

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function setData(array $data): static
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = (string) $value;

        // --- Complexity checks ---
        if (mb_strlen($value) < 8) {
            $fail('Password minimal 8 karakter.');

            return;
        }

        if (! preg_match('/[A-Z]/', $value)) {
            $fail('Password harus mengandung huruf besar.');

            return;
        }

        if (! preg_match('/[a-z]/', $value)) {
            $fail('Password harus mengandung huruf kecil.');

            return;
        }

        if (! preg_match('/[0-9]/', $value)) {
            $fail('Password harus mengandung angka.');

            return;
        }

        if (! preg_match('/[^A-Za-z0-9]/', $value)) {
            $fail('Password harus mengandung karakter khusus (contoh: @, #, $, !).');

            return;
        }

        // --- Password history check ---
        $userId = $this->data['user_id'] ?? null;

        if ($userId) {
            $historyCount = config('uam.password_history_count', 5);

            $recentPasswords = PasswordHistory::query()
                ->where('user_id', $userId)
                ->orderByDesc('created_at')
                ->limit($historyCount)
                ->pluck('password');

            foreach ($recentPasswords as $oldHash) {
                if (Hash::check($value, $oldHash)) {
                    $fail("Password tidak boleh sama dengan {$historyCount} password terakhir.");

                    return;
                }
            }
        }

        // --- Cannot reuse current password ---
        $currentHash = $this->data['current_password_hash'] ?? null;

        if ($currentHash && Hash::check($value, $currentHash)) {
            $fail('Password baru tidak boleh sama dengan password saat ini.');
        }
    }
}
