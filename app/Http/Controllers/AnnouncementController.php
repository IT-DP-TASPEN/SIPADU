<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function __construct(
        protected AuditService $audit,
    ) {}

    public function index(Request $request): View
    {
        $announcements = Announcement::query()
            ->with('creator:id,name')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('announcements.index', [
            'announcements' => $announcements,
        ]);
    }

    public function create(): View
    {
        return view('announcements.create', [
            'announcement' => new Announcement([
                'status' => Announcement::STATUS_DRAFT,
                'priority' => Announcement::PRIORITY_MEDIUM,
                'target_type' => 'all',
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'priority' => ['required', 'in:high,medium,low'],
            'publish_at' => ['nullable', 'date'],
            'expire_at' => ['nullable', 'date', 'after:publish_at'],
            'target_type' => ['required', 'in:all,unit,branch,user'],
            'target_value' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published,archived'],
        ]);

        $data['created_by'] = $request->user()->id;

        $announcement = Announcement::query()->create($data);

        $this->audit->log('Announcement Created', $request->user(), "Announcement: {$announcement->title}");

        if ($data['status'] === Announcement::STATUS_PUBLISHED) {
            $this->audit->log('Announcement Published', $request->user(), "Published: {$announcement->title}");
        }

        return redirect()
            ->route('announcements.index')
            ->with('status', "Pengumuman \"{$announcement->title}\" berhasil ditambahkan.");
    }

    public function edit(Announcement $announcement): View
    {
        return view('announcements.edit', [
            'announcement' => $announcement,
        ]);
    }

    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'priority' => ['required', 'in:high,medium,low'],
            'publish_at' => ['nullable', 'date'],
            'expire_at' => ['nullable', 'date', 'after:publish_at'],
            'target_type' => ['required', 'in:all,unit,branch,user'],
            'target_value' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published,archived'],
        ]);

        $wasPublished = $announcement->status === Announcement::STATUS_PUBLISHED;
        $announcement->update($data);

        $this->audit->log('Announcement Updated', $request->user(), "Updated: {$announcement->title}");

        if (! $wasPublished && $data['status'] === Announcement::STATUS_PUBLISHED) {
            $this->audit->log('Announcement Published', $request->user(), "Published: {$announcement->title}");
        }

        return redirect()
            ->route('announcements.index')
            ->with('status', "Pengumuman \"{$announcement->title}\" berhasil diperbarui.");
    }

    public function destroy(Request $request, Announcement $announcement): RedirectResponse
    {
        $title = $announcement->title;
        $announcement->delete();

        $this->audit->log('Announcement Deleted', $request->user(), "Deleted: {$title}");

        return redirect()
            ->route('announcements.index')
            ->with('status', "Pengumuman \"{$title}\" berhasil dihapus.");
    }
}

