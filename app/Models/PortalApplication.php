<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PortalApplication extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::saving(function (PortalApplication $application): void {
            $application->sso_enabled = $application->launch_mode === 'sso';

            if (! $application->usesSso()) {
                $application->sso_login_url = null;
                $application->sso_audience = null;
                $application->sso_shared_secret = null;

                return;
            }

            $application->sso_login_url = $application->sso_login_url ?: $application->url;
            $application->sso_audience = $application->sso_audience ?: $application->slug;
            $application->sso_shared_secret = $application->sso_shared_secret ?: static::generateSharedSecret();
        });
    }

    protected $fillable = [
        'name',
        'slug',
        'description',
        'url',
        'sso_login_url',
        'badge',
        'icon',
        'accent_color',
        'keywords',
        'sort_order',
        'is_frequent',
        'is_active',
        'launch_mode',
        'sso_enabled',
        'open_in_new_tab',
        'sso_audience',
        'sso_shared_secret',
    ];

    protected function casts(): array
    {
        return [
            'keywords' => 'array',
            'is_frequent' => 'boolean',
            'is_active' => 'boolean',
            'sso_enabled' => 'boolean',
            'open_in_new_tab' => 'boolean',
        ];
    }

    public function getIconUrl(): string|null
    {
        if (!$this->icon) {
            return null;
        }

        if (str_starts_with($this->icon, 'icons/') || str_starts_with($this->icon, 'http')) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->icon);
        }

        return null; // It's a preset icon name
    }

    public function isCustomIcon(): bool
    {
        return $this->icon && (str_starts_with($this->icon, 'icons/') || str_starts_with($this->icon, 'http'));
    }

    public function usesSso(): bool
    {
        return $this->launch_mode === 'sso';
    }

    public static function generateSharedSecret(): string
    {
        return Str::random(64);
    }

    public function isLaunchOnly(): bool
    {
        return $this->launch_mode === 'launch_only';
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function launchLogs(): HasMany
    {
        return $this->hasMany(SsoLaunchLog::class, 'portal_application_id');
    }

    public function accessRules(): HasMany
    {
        return $this->hasMany(PortalApplicationAccessRule::class)->orderBy('priority');
    }

    public function isAccessibleBy(User $user): bool
    {
        $rules = $this->relationLoaded('accessRules') ? $this->accessRules : $this->accessRules()->get();

        if ($rules->isEmpty()) {
            return true;
        }

        return $rules->contains(fn (PortalApplicationAccessRule $rule) => $this->matchesRule($user, $rule));
    }

    private function matchesRule(User $user, PortalApplicationAccessRule $rule): bool
    {
        return $this->matchesValue($rule->division_name, $user->division_name)
            && $this->matchesValue($rule->job_title, $user->title)
            && $this->matchesValue($rule->office_type, $user->office_type)
            && $this->matchesValue($rule->branch_code, $user->branch_code)
            && $this->matchesValue($rule->branch_name, $user->branch_name);
    }

    private function matchesValue(?string $ruleValue, ?string $userValue): bool
    {
        if ($ruleValue === null || $ruleValue === '') {
            return true;
        }

        if ($userValue === null || $userValue === '') {
            return false;
        }

        return mb_strtolower(trim($ruleValue)) === mb_strtolower(trim($userValue));
    }
}
