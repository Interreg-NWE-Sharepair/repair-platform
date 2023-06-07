<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperDeviceNote
 */
class DeviceNote extends Model
{
    use HasFactory;

    protected $with = [
        'device',
    ];

    protected $fillable = ['content'];

    protected $appends = ['created_timestamp'];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function getCreatedTimestampAttribute()
    {
        if ($this->created_at) {
            return $this->created_at->isoFormat('DD MMMM YYYY');
        }
    }
}
