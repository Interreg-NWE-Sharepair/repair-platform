<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperRepairGuidanceFormDeviceTypeIssue
 */
class RepairGuidanceFormDeviceTypeIssue extends Model
{
    use HasFactory;

    protected $table = 'guidance_device_type_issues';

    public function commonDeviceTypeIssue()
    {
        return $this->belongsTo(CommonDeviceTypeIssue::class);
    }

    public function repairGuidanceLog()
    {
        return $this->belongsTo(RepairGuidanceFormLog::class);
    }
}
