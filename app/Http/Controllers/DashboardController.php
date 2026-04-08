<?php

namespace App\Http\Controllers;

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

        return view('dashboard.index', [
            'summary' => $summary,
            'topUsers' => $topUsers,
            'topApplications' => $topApplications,
            'recentLaunches' => $recentLaunches,
        ]);
    }
}
