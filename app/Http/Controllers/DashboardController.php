<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AuditLog;
use App\Models\Notification;
use App\Models\PortalApplication;
use App\Models\SsoLaunchLog;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = now()->startOfDay();
        $sevenDaysAgo = now()->subDays(7);
        $thirtyDaysAgo = now()->subDays(30);

        // UAM Summary
        $uamSummary = [
            'total_active' => User::query()->where('status', User::STATUS_ACTIVE)->count(),
            'total_inactive' => User::query()->where('status', User::STATUS_INACTIVE)->count(),
            'total_expired' => User::query()->where('status', User::STATUS_EXPIRED)->count(),
            'total_locked' => User::query()->where('status', User::STATUS_LOCKED)->count(),
            'password_expired' => User::query()
                ->where('status', User::STATUS_ACTIVE)
                ->whereNotNull('password_changed_at')
                ->where('password_changed_at', '<', now()->subDays(config('uam.password_max_age_days', 30)))
                ->count(),
            'password_expiring_soon' => User::query()
                ->where('status', User::STATUS_ACTIVE)
                ->whereNotNull('password_changed_at')
                ->whereBetween(
                    'password_changed_at',
                    [
                        now()->subDays(config('uam.password_max_age_days', 30)),
                        now()->subDays(config('uam.password_max_age_days', 30) - 7),
                    ]
                )
                ->count(),
            'logins_today' => AuditLog::query()
                ->where('activity', 'Login Success')
                ->where('created_at', '>=', $today)
                ->count(),
            'failed_logins_today' => AuditLog::query()
                ->where('activity', 'like', 'Login Failed%')
                ->where('created_at', '>=', $today)
                ->count(),
            'active_announcements' => Announcement::query()->active()->count(),
            'unread_notifications' => Notification::query()->where('is_read', false)->count(),
        ];

        // Existing portal summary
        $summary = [
            'total_applications' => PortalApplication::query()->count(),
            'active_applications' => PortalApplication::query()->where('is_active', true)->count(),
            'sso_applications' => PortalApplication::query()->where('launch_mode', 'sso')->count(),
            'launch_only_applications' => PortalApplication::query()->where('launch_mode', 'launch_only')->count(),
            'total_users' => User::query()->count(),
            'active_users_30d' => SsoLaunchLog::query()
                ->where('launched_at', '>=', $thirtyDaysAgo)
                ->distinct('user_id')
                ->count('user_id'),
            'launches_today' => SsoLaunchLog::query()
                ->where('launched_at', '>=', $today)
                ->count(),
            'launches_7d' => SsoLaunchLog::query()
                ->where('launched_at', '>=', $sevenDaysAgo)
                ->count(),
            'launches_30d' => SsoLaunchLog::query()
                ->where('launched_at', '>=', $thirtyDaysAgo)
                ->count(),
        ];

        $topUsers = SsoLaunchLog::query()
            ->select('user_id')
            ->selectRaw('COUNT(*) as launches_count')
            ->selectRaw('MAX(launched_at) as last_launched_at')
            ->with('user:id,name,employee_id,division_name,title')
            ->groupBy('user_id')
            ->orderByDesc('launches_count')
            ->orderByDesc('last_launched_at')
            ->limit(8)
            ->get();

        $topApplications = SsoLaunchLog::query()
            ->select('portal_application_id')
            ->selectRaw('COUNT(*) as launches_count')
            ->selectRaw('MAX(launched_at) as last_launched_at')
            ->with('application:id,name,badge,launch_mode')
            ->groupBy('portal_application_id')
            ->orderByDesc('launches_count')
            ->orderByDesc('last_launched_at')
            ->limit(8)
            ->get();

        $recentLaunches = SsoLaunchLog::query()
            ->with([
                'user:id,name,employee_id,division_name',
                'application:id,name,launch_mode,badge',
            ])
            ->latest('launched_at')
            ->limit(12)
            ->get();

        // Recent audit logs
        $recentAuditLogs = AuditLog::query()
            ->orderByDesc('created_at')
            ->limit(15)
            ->get();

        return view('dashboard.index', [
            'summary' => $summary,
            'uamSummary' => $uamSummary,
            'topUsers' => $topUsers,
            'topApplications' => $topApplications,
            'recentLaunches' => $recentLaunches,
            'recentAuditLogs' => $recentAuditLogs,
        ]);
    }
}
