<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperRepairLogBarrier
 */
class RepairLogBarrier extends Model
{
    public function repairLog()
    {
        return $this->belongsTo(RepairLog::class);
    }

    public function repairBarrier()
    {
        return $this->belongsTo(RepairBarrier::class);
    }
}
