<?php

namespace App\Http\Controllers;

use App\Models\PortalApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortalController extends Controller
{
    public function index(Request $request): View
    {
        $applications = PortalApplication::query()
            ->where('is_active', true)
            ->with('accessRules')
            ->orderByDesc('is_frequent')
            ->orderBy('sort_order')
            ->get();

        $accessibleApplications = $applications;

        return view('portal.index', [
            'user' => $request->user(),
            'frequentApps' => $accessibleApplications->where('is_frequent', true)->values(),
            'allApps' => $accessibleApplications,
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $query = trim((string) $request->string('q'));

        $applications = PortalApplication::query()
            ->where('is_active', true)
            ->with('accessRules')
            ->get()
            ->map(fn (PortalApplication $application) => [
                'id' => $application->id,
                'name' => $application->name,
                'slug' => $application->slug,
                'description' => $application->description,
                'badge' => $application->badge,
                'keywords' => $application->keywords ?? [],
                'launch_url' => route('portal.launch', $application),
                'icon' => $application->icon,
                'accent_color' => $application->accent_color,
                'launch_mode' => $application->launch_mode,
                'score' => $this->scoreApplication($application, $query),
            ])
            ->filter(fn (array $application) => $query === '' || $application['score'] > 0)
            ->sortByDesc('score')
            ->values()
            ->take(8);

        return response()->json([
            'data' => $applications,
        ]);
    }

    private function scoreApplication(PortalApplication $application, string $query): int
    {
        if ($query === '') {
            return 0;
        }

        $needle = mb_strtolower($query);
        $name = mb_strtolower($application->name);
        $description = mb_strtolower($application->description ?? '');
        $keywords = collect($application->keywords ?? [])->map(fn (string $keyword) => mb_strtolower($keyword));

        return match (true) {
            $name === $needle => 100,
            str_contains($name, $needle) => 80,
            $keywords->contains(fn (string $keyword) => str_contains($keyword, $needle)) => 60,
            str_contains($description, $needle) => 40,
            default => 0,
        };
    }
}
