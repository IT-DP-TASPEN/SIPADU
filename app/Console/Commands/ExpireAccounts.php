<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\AuditService;
use Illuminate\Console\Command;

class ExpireAccounts extends Command
{
    protected $signature = 'uam:expire-accounts';
    protected $description = 'Expire user accounts that have passed their active_until date';

    public function __construct(
        protected AuditService $audit,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $users = User::query()
            ->where('status', User::STATUS_ACTIVE)
            ->whereNotNull('active_until')
            ->where('active_until', '<', now()->startOfDay())
            ->get();

        $count = 0;

        foreach ($users as $user) {
            $user->forceFill(['status' => User::STATUS_EXPIRED])->save();

            $this->audit->log('Account Expired', $user, 'Expired by System (scheduler)');

            $user->uamNotifications()->create([
                'type' => 'security',
                'title' => 'Akun Anda Telah Kedaluwarsa',
                'body' => 'Masa berlaku akun Anda telah berakhir. Hubungi Administrator untuk memperpanjang.',
                'priority' => 'high',
                'created_at' => now(),
            ]);

            $count++;
        }

        $this->info("Expired {$count} account(s).");

        return self::SUCCESS;
    }
}
