<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Console\Command;

class SendPasswordExpiryNotifications extends Command
{
    protected $signature = 'uam:password-expiry-notify';
    protected $description = 'Send password expiry notifications at H-7, H-3, H-1';

    public function handle(): int
    {
        $notifyDays = config('uam.password_notify_days', [7, 3, 1]);
        $maxAge = config('uam.password_max_age_days', 30);
        $count = 0;

        $users = User::query()
            ->where('status', User::STATUS_ACTIVE)
            ->whereNotNull('password_changed_at')
            ->get();

        foreach ($users as $user) {
            $daysRemaining = $user->passwordDaysRemaining();

            if ($daysRemaining === null) {
                continue;
            }

            if (in_array($daysRemaining, $notifyDays)) {
                // Check if notification already sent today for this user
                $existing = Notification::query()
                    ->where('user_id', $user->id)
                    ->where('type', 'security')
                    ->where('title', 'like', 'Password akan kedaluwarsa%')
                    ->whereDate('created_at', now()->toDateString())
                    ->exists();

                if (! $existing) {
                    Notification::query()->create([
                        'user_id' => $user->id,
                        'type' => 'security',
                        'title' => "Password akan kedaluwarsa dalam {$daysRemaining} hari",
                        'body' => "Password Anda akan kedaluwarsa pada {$user->passwordExpiresAt()->format('d M Y')}. Segera ganti password Anda.",
                        'priority' => $daysRemaining <= 1 ? 'high' : 'medium',
                        'created_at' => now(),
                    ]);

                    $count++;
                }
            }
        }

        $this->info("Sent {$count} password expiry notification(s).");

        return self::SUCCESS;
    }
}
