<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RepairLogLink
 *
 * @property \App\Models\RepairLog $repairLog
 * @property string url
 * @mixin IdeHelperRepairLogLink
 */
class RepairLogLink extends Model
{
    public function repairLog()
    {
        return $this->belongsTo(RepairLog::class);
    }
}
