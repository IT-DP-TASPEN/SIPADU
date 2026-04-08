<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortalApplicationAccessRule extends Model
{
    protected $fillable = [
        'portal_application_id',
        'division_name',
        'job_title',
        'office_type',
        'branch_code',
        'branch_name',
        'priority',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(PortalApplication::class, 'portal_application_id');
    }
}
