<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = Notification::query()
            ->where(function ($q) use ($request) {
                $q->where('user_id', $request->user()->id)
                    ->orWhereNull('user_id'); // global notifications
            })
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('notifications.index', [
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(Request $request, Notification $notification): RedirectResponse
    {
        if ($notification->user_id === null || $notification->user_id === $request->user()->id) {
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return back();
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        Notification::query()
            ->where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return back()->with('status', 'Semua notifikasi telah ditandai dibaca.');
    }

    /**
     * API endpoint for external apps to push notifications (future-ready).
     */
    public function apiStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'type' => ['required', 'in:security,system,announcement'],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'priority' => ['nullable', 'in:high,medium,low'],
        ]);

        $notification = Notification::query()->create([
            'user_id' => $validated['user_id'] ?? null,
            'type' => $validated['type'],
            'title' => $validated['title'],
            'body' => $validated['body'] ?? null,
            'priority' => $validated['priority'] ?? 'medium',
            'created_at' => now(),
        ]);

        return response()->json(['data' => $notification], 201);
    }
}
