<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('q'));
        $activity = $request->string('activity')->value();

        $logs = AuditLog::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder
                        ->where('user_name', 'like', "%{$search}%")
                        ->orWhere('activity', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('ip_address', 'like', "%{$search}%");
                });
            })
            ->when($activity !== '', function ($query) use ($activity) {
                $query->where('activity', 'like', "%{$activity}%");
            })
            ->orderByDesc('created_at')
            ->paginate(30)
            ->withQueryString();

        return view('audit-logs.index', [
            'logs' => $logs,
            'search' => $search,
            'activity' => $activity,
        ]);
    }
}
