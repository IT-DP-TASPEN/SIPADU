<?php

namespace App\Http\Controllers;

use App\Models\PortalApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PortalApplicationController extends Controller
{
    public function index(): View
    {
        return view('portal-applications.index', [
            'applications' => PortalApplication::query()->orderBy('sort_order')->orderBy('name')->get(),
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
            'access_rules' => ['nullable', 'string'],
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

        collect(preg_split('/\r\n|\r|\n/', (string) $request->input('access_rules', '')))
            ->map(fn (string $line) => trim($line))
            ->filter()
            ->values()
            ->each(function (string $line, int $index) use ($application): void {
                [$division, $jobTitle, $officeType, $branchCode, $branchName] = array_pad(
                    array_map(fn (string $segment) => $this->normalizeRuleValue($segment), explode('|', $line)),
                    5,
                    null,
                );

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
