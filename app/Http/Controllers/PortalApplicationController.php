<?php

namespace App\Http\Controllers;

use App\Models\PortalApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PortalApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('q');
        $mode = $request->query('mode');
        $status = $request->query('status');

        $applications = PortalApplication::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($mode, function ($query, $mode) {
                $query->where('launch_mode', $mode);
            })
            ->when($status, function ($query, $status) {
                if ($status === 'active') {
                    $query->where('is_active', true);
                } elseif ($status === 'inactive') {
                    $query->where('is_active', false);
                } elseif ($status === 'frequent') {
                    $query->where('is_frequent', true);
                }
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('portal-applications.index', [
            'applications' => $applications,
            'search' => $search,
            'mode' => $mode,
            'status' => $status,
        ]);
    }

    public function create(): View
    {
        return view('portal-applications.create', [
            'application' => new PortalApplication([
                'launch_mode' => 'sso',
                'is_active' => true,
                'is_frequent' => false,
                'open_in_new_tab' => true,
                'sso_enabled' => true,
            ]),
            'accessRulesText' => '',
            'divisions' => \App\Models\User::distinct()->orderBy('division_name')->pluck('division_name')->filter()->values(),
            'titles' => \App\Models\User::distinct()->orderBy('title')->pluck('title')->filter()->values(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $application = PortalApplication::query()->create($this->validatedData($request));
        $this->syncAccessRules($request, $application);

        return redirect()
            ->route('portal-applications.index')
            ->with('status', "Aplikasi {$application->name} berhasil ditambahkan.");
    }

    public function edit(PortalApplication $portalApplication): View
    {
        return view('portal-applications.edit', [
            'application' => $portalApplication,
            'accessRulesText' => $this->formatAccessRules($portalApplication),
            'divisions' => \App\Models\User::distinct()->orderBy('division_name')->pluck('division_name')->filter()->values(),
            'titles' => \App\Models\User::distinct()->orderBy('title')->pluck('title')->filter()->values(),
            'existingRules' => $portalApplication->accessRules()->get()->map(function($rule) {
                return $rule->division_name ?: $rule->job_title;
            })->filter()->values()->all(),
        ]);
    }

    public function update(Request $request, PortalApplication $portalApplication): RedirectResponse
    {
        $portalApplication->update($this->validatedData($request, $portalApplication));
        $this->syncAccessRules($request, $portalApplication);

        return redirect()
            ->route('portal-applications.index')
            ->with('status', "Aplikasi {$portalApplication->name} berhasil diperbarui.");
    }

    public function destroy(PortalApplication $portalApplication): RedirectResponse
    {
        $name = $portalApplication->name;
        $portalApplication->delete();

        return redirect()
            ->route('portal-applications.index')
            ->with('status', "Aplikasi {$name} berhasil dihapus.");
    }

    private function validatedData(Request $request, ?PortalApplication $application = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('portal_applications', 'slug')->ignore($application?->id)],
            'description' => ['nullable', 'string'],
            'url' => ['required', 'url', 'max:255'],
            'sso_login_url' => ['nullable', 'url', 'max:255'],
            'badge' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'accent_color' => ['nullable', 'string', 'max:50'],
            'keywords' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'launch_mode' => ['required', Rule::in(['sso', 'launch_only'])],
            'sso_audience' => ['nullable', 'string', 'max:255'],
            'sso_shared_secret' => ['nullable', 'string', 'max:255'],
            'regenerate_sso_shared_secret' => ['nullable', 'boolean'],
            'is_frequent' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'open_in_new_tab' => ['nullable', 'boolean'],
            'access_rules' => ['nullable', 'array'],
            'access_rules.*' => ['string'],
        ]);

        $data['slug'] = $data['slug'] ?: str($data['name'])->slug()->value();
        $data['keywords'] = $this->normalizeKeywords($data['keywords'] ?? '');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_frequent'] = $request->boolean('is_frequent');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['open_in_new_tab'] = $request->boolean('open_in_new_tab', true);
        $data['sso_enabled'] = $data['launch_mode'] === 'sso';
        $data['sso_login_url'] = $data['launch_mode'] === 'sso' ? ($data['sso_login_url'] ?: $data['url']) : null;
        $data['sso_audience'] = $data['launch_mode'] === 'sso' ? ($data['sso_audience'] ?: $data['slug']) : null;
        $data['sso_shared_secret'] = $data['launch_mode'] === 'sso' ? (($data['sso_shared_secret'] ?? null) ?: null) : null;

        if ($request->boolean('regenerate_sso_shared_secret') && $data['launch_mode'] === 'sso') {
            $data['sso_shared_secret'] = PortalApplication::generateSharedSecret();
        }

        unset($data['regenerate_sso_shared_secret']);

        return $data;
    }

    private function syncAccessRules(Request $request, PortalApplication $application): void
    {
        $application->accessRules()->delete();

        $rules = $request->input('access_rules', []);
        
        if (is_string($rules)) {
            $rules = collect(preg_split('/\r\n|\r|\n/', $rules))
                ->map(fn (string $line) => trim($line))
                ->filter()
                ->values()
                ->all();
        }

        collect($rules)->each(function (string $selection, int $index) use ($application): void {
            // Check if it's a pipe-separated rule or just a single value (division/title)
            if (str_contains($selection, '|')) {
                [$division, $jobTitle, $officeType, $branchCode, $branchName] = array_pad(
                    array_map(fn (string $segment) => $this->normalizeRuleValue($segment), explode('|', $selection)),
                    5,
                    null,
                );
            } else {
                // If it's a single value from our new multi-select, we need to decide if it's a division or a title.
                // For simplicity, we'll check against our known lists or just try both.
                // In a production system, we might want a prefix like "div:" or "title:".
                $isDivision = \App\Models\User::where('division_name', $selection)->exists();
                $division = $isDivision ? $selection : null;
                $jobTitle = !$isDivision ? $selection : null;
                $officeType = null;
                $branchCode = null;
                $branchName = null;
            }

            $application->accessRules()->create([
                'division_name' => $division,
                'job_title' => $jobTitle,
                'office_type' => $officeType,
                'branch_code' => $branchCode,
                'branch_name' => $branchName,
                'priority' => $index + 1,
            ]);
        });
    }

    private function formatAccessRules(PortalApplication $application): string
    {
        return $application->accessRules()
            ->get()
            ->map(function ($rule): string {
                return implode(' | ', [
                    $rule->division_name ?: '*',
                    $rule->job_title ?: '*',
                    $rule->office_type ?: '*',
                    $rule->branch_code ?: '*',
                    $rule->branch_name ?: '*',
                ]);
            })
            ->implode(PHP_EOL);
    }

    private function normalizeKeywords(string $keywords): array
    {
        return collect(explode(',', $keywords))
            ->map(fn (string $keyword) => trim($keyword))
            ->filter()
            ->values()
            ->all();
    }

    private function normalizeRuleValue(string $value): ?string
    {
        $normalized = trim($value);

        return $normalized === '' || $normalized === '*' ? null : $normalized;
    }
}
