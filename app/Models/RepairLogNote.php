<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RepairLogLink
 *
 * @property RepairLog $repairLog
 * @property string url
 * @property \Carbon\Carbon $created_at
 * @mixin IdeHelperRepairLogNote
 */
class RepairLogNote extends Model
{
    protected $with = [
        'repairLog',
    ];

    protected $fillable = ['content'];

    protected $appends = ['created_timestamp'];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function repairLog()
    {
        return $this->belongsTo(RepairLog::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function getCreatedTimestampAttribute()
    {
        if ($this->created_at) {
            return $this->created_at->isoFormat('DD MMMM YYYY');
        }
    }
}
