<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SsoLaunchLog extends Model
{
    protected $fillable = [
        'user_id',
        'portal_application_id',
        'target_url',
        'token_id',
        'token_expires_at',
        'launched_at',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'token_expires_at' => 'datetime',
            'launched_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(PortalApplication::class, 'portal_application_id');
    }
}
