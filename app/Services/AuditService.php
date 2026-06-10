<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditService
{
    public function log(
        string $activity,
        ?User $user = null,
        string $description = '',
        ?Request $request = null,
    ): AuditLog {
        $request = $request ?? request();

        return AuditLog::query()->create([
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'activity' => $activity,
            'ip_address' => $request->ip(),
            'browser' => $this->parseBrowser($request->userAgent() ?? ''),
            'device' => $this->parseDevice($request->userAgent() ?? ''),
            'description' => $description,
            'created_at' => now(),
        ]);
    }

    protected function parseBrowser(string $userAgent): string
    {
        if (str_contains($userAgent, 'Edg')) {
            return 'Edge';
        }
        if (str_contains($userAgent, 'Chrome')) {
            return 'Chrome';
        }
        if (str_contains($userAgent, 'Firefox')) {
            return 'Firefox';
        }
        if (str_contains($userAgent, 'Safari')) {
            return 'Safari';
        }

        return 'Other';
    }

    protected function parseDevice(string $userAgent): string
    {
        if (preg_match('/iPhone|iPad|iPod/i', $userAgent)) {
            return 'iOS';
        }
        if (preg_match('/Android/i', $userAgent)) {
            return 'Android';
        }
        if (preg_match('/Windows/i', $userAgent)) {
            return 'Windows';
        }
        if (preg_match('/Macintosh/i', $userAgent)) {
            return 'macOS';
        }
        if (preg_match('/Linux/i', $userAgent)) {
            return 'Linux';
        }

        return 'Unknown';
    }
}
