<?php

namespace App\Http\Controllers;

use App\Models\PortalApplication;
use App\Models\SsoLaunchLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SsoLogController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('q'));
        $applicationId = $request->integer('application_id');
        $launchMode = trim((string) $request->string('launch_mode'));
        $dateFrom = trim((string) $request->string('date_from'));
        $dateTo = trim((string) $request->string('date_to'));

        $baseQuery = SsoLaunchLog::query()
            ->with(['user:id,name,employee_id', 'application:id,name,slug,launch_mode'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder
                        ->whereHas('user', function ($userQuery) use ($search) {
                            $userQuery
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('employee_id', 'like', "%{$search}%");
                        })
                        ->orWhereHas('application', function ($applicationQuery) use ($search) {
                            $applicationQuery
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('slug', 'like', "%{$search}%");
                        })
                        ->orWhere('target_url', 'like', "%{$search}%")
                        ->orWhere('token_id', 'like', "%{$search}%");
                });
            })
            ->when($applicationId > 0, fn ($query) => $query->where('portal_application_id', $applicationId))
            ->when(in_array($launchMode, ['sso', 'launch_only'], true), fn ($query) => $query->where('launch_mode_snapshot', $launchMode))
            ->when($dateFrom !== '', fn ($query) => $query->whereDate('launched_at', '>=', $dateFrom))
            ->when($dateTo !== '', fn ($query) => $query->whereDate('launched_at', '<=', $dateTo));

        $logs = (clone $baseQuery)
            ->latest('launched_at')
            ->paginate(20)
            ->withQueryString();

        $summary = [
            'total_logs' => (clone $baseQuery)->count(),
            'sso_logs' => (clone $baseQuery)->where('launch_mode_snapshot', 'sso')->count(),
            'launch_only_logs' => (clone $baseQuery)->where('launch_mode_snapshot', 'launch_only')->count(),
            'unique_users' => (clone $baseQuery)->distinct('user_id')->count('user_id'),
            'unique_applications' => (clone $baseQuery)->distinct('portal_application_id')->count('portal_application_id'),
        ];

        return view('sso-logs.index', [
            'logs' => $logs,
            'search' => $search,
            'applicationId' => $applicationId,
            'launchMode' => $launchMode,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'summary' => $summary,
            'applications' => PortalApplication::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function show(SsoLaunchLog $ssoLog): View
    {
        $ssoLog->load(['user:id,name,employee_id,email,division_name,title', 'application:id,name,slug,launch_mode,sso_audience']);

        return view('sso-logs.show', [
            'log' => $ssoLog,
        ]);
    }
}
