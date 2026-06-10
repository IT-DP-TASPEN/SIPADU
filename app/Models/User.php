<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name', 'username', 'email', 'phone', 'password',
    'employee_id', 'title', 'unit_name', 'division_name',
    'office_type', 'branch_code', 'branch_name',
    'is_admin', 'last_login_at',
    'status', 'active_from', 'active_until',
    'password_changed_at', 'must_change_password',
    'login_failure_count', 'locked_at',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // Status constants
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_LOCKED = 'locked';

    public const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE,
        self::STATUS_EXPIRED,
        self::STATUS_LOCKED,
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_admin' => 'boolean',
            'active_from' => 'date',
            'active_until' => 'date',
            'password_changed_at' => 'datetime',
            'must_change_password' => 'boolean',
            'login_failure_count' => 'integer',
            'locked_at' => 'datetime',
        ];
    }

    /* ------------------------------------------------------------------
     * Relationships
     * ----------------------------------------------------------------*/

    public function ssoLaunchLogs(): HasMany
    {
        return $this->hasMany(SsoLaunchLog::class);
    }

    public function passwordHistories(): HasMany
    {
        return $this->hasMany(PasswordHistory::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function uamNotifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /* ------------------------------------------------------------------
     * Status helpers
     * ----------------------------------------------------------------*/

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this->status === self::STATUS_INACTIVE;
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED;
    }

    public function isLocked(): bool
    {
        return $this->status === self::STATUS_LOCKED;
    }

    public function isAccountExpired(): bool
    {
        return $this->active_until !== null && $this->active_until->isPast();
    }

    public function isPasswordExpired(): bool
    {
        if (! $this->password_changed_at) {
            return true;
        }

        $maxAge = config('uam.password_max_age_days', 30);

        return $this->password_changed_at->copy()->addDays($maxAge)->isPast();
    }

    public function passwordExpiresAt(): ?\Illuminate\Support\Carbon
    {
        if (! $this->password_changed_at) {
            return null;
        }

        $maxAge = config('uam.password_max_age_days', 30);

        return $this->password_changed_at->copy()->addDays($maxAge);
    }

    public function passwordDaysRemaining(): ?int
    {
        $expires = $this->passwordExpiresAt();

        if (! $expires) {
            return null;
        }

        $days = now()->diffInDays($expires, false);

        return (int) $days;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'Aktif',
            self::STATUS_INACTIVE => 'Nonaktif',
            self::STATUS_EXPIRED => 'Expired',
            self::STATUS_LOCKED => 'Locked',
            default => ucfirst($this->status ?? 'unknown'),
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'bg-brand-400/10 text-brand-200 border-brand-400/20',
            self::STATUS_INACTIVE => 'bg-slate-400/10 text-slate-300 border-slate-400/20',
            self::STATUS_EXPIRED => 'bg-rose-400/10 text-rose-300 border-rose-400/20',
            self::STATUS_LOCKED => 'bg-amber-400/10 text-amber-300 border-amber-400/20',
            default => 'bg-white/5 text-slate-300 border-white/10',
        };
    }
}
