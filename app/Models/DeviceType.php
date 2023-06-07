<?php

namespace App\Models;

use App\Traits\HasQueryParameters;
use App\Traits\HasTranslations;
use App\Traits\HasUuid;
use App\Traits\HasVisibility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * Class DeviceType
 * 
 * Device types come from here:
 * https://github.com/TheRestartProject/restarters.net/wiki/Repair-Data-fields
 *
 * @mixin IdeHelperDeviceType
 */
class DeviceType extends Model
{
    use HasTranslations;
    use HasUuid;
    use HasSlug;
    use SoftDeletes;
    use HasVisibility;
    use HasQueryParameters;

    protected $fillable = [
        'uuid',
        'code',
        'name',
    ];

    public $translatable = [
        'name',
        'repair_success_rate',
        'eco_impact',
    ];

    protected $hidden = ['id'];

    public $appends = ['locale_name'];

    protected $with = ['parent'];

    public function parent()
    {
        return $this->belongsTo(self::class);
    }

    public function hasParent()
    {
        return optional($this->parent)->exists ?? false;
    }

    public function commonDeviceTypeIssues()
    {
        return $this->hasMany(CommonDeviceTypeIssue::class);
    }

    public function repairTutorials()
    {
        return $this->hasMany(RepairTutorial::class);
    }

    public function getLocaleNameAttribute()
    {
        return $this->name;
    }

    /**
     * Returns all the device types that are visibible at this moment
     */
    public function allDeviceTypes()
    {
        return self::visible()->get();
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', 1);
    }

    public function scopeShowOnGuidance($query)
    {
        return $query->where('show_on_guidance', 1);
    }

    public function scopeShowOnRepair($query)
    {
        return $query->where('show_on_connects', 1);
    }

    public function scopeShowOnMapping($query)
    {
        return $query->where('show_on_mapping', 1);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom(['name'])->saveSlugsTo('code')->usingLanguage('nl');
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param mixed $value
     * @param string|null $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where(function ($query) use ($value, $field) {
            $query->where($field ?? $this->getRouteKeyName(), $value)->orWhere('code', $value);
        })->first();
    }
}
