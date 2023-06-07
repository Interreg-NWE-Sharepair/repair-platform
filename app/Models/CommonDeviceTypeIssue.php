<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCommonDeviceTypeIssue
 */
class CommonDeviceTypeIssue extends Model
{
    use HasTranslations;

    public $translatable = ['issue', 'description'];

    protected $hidden = ['id'];

    protected $with = [
        'deviceType',
    ];

    public function deviceType()
    {
        return $this->belongsTo(DeviceType::class);
    }

    public function repairTutorials()
    {
        return $this->hasMany(RepairTutorial::class);
    }
}
