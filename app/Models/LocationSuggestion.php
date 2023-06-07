<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

/**
 * @mixin IdeHelperLocationSuggestion
 */
class LocationSuggestion extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasJsonRelationships;
    use HasTranslations;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'product_description',
        'has_warranty',
        'warranty_info',
        'address',
        'device_types',
        'activity_sectors',
        'contacts',
        'locales',
        'submitter_email',
        'submitter_relation',
        'original_details',
        'is_approved',
    ];

    public $translatable = [
        'name',
        'description',
        'product_description',
        'warranty_info',
    ];

    protected $casts = [
        'has_warranty' => 'boolean',
        'approved_at' => 'date',
        'address' => 'json',
        'device_types' => 'json',
        'activity_sectors' => 'json',
        'contacts' => 'json',
        'locales' => 'json',
        'original_details' => 'json',
    ];

    protected $hidden = [
        'id',
        'original_details',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function organisationType()
    {
        return $this->belongsTo(OrganisationType::class);
    }

    public function deviceTypes()
    {
        return $this->belongsToJson(DeviceType::class, 'device_types');
    }

    public function activitySectors()
    {
        return $this->belongsToJson(ActivitySector::class, 'activity_sectors');
    }

    /**
     * Overwrite getAttributeValue to prevent conflict
     * between HasJsonRelationships and HasTranslations
     *
     * @param string $key
     * @return mixed
     * @see HasJsonRelationships
     * @see HasTranslations
     */
    public function getAttributeValue($key)
    {
        if (Str::contains($key, '->')) {
            [
                $key,
                $path,
            ] = explode('->', $key, 2);

            if (substr($key, -2) === '[]') {
                $key = substr($key, 0, -2);

                $path = '*.' . $path;
            }

            $path = str_replace([
                '->',
                '[]',
            ], [
                '.',
                '.*',
            ], $path);

            return data_get($this->getAttributeValue($key), $path);
        }

        if (!$this->isTranslatableAttribute($key)) {
            return parent::getAttributeValue($key);
        }

        return $this->getTranslation($key, $this->getLocale());
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile()->withResponsiveImages()->useDisk('digitalocean');

        $this->addMediaCollection('images')->withResponsiveImages()->useDisk('digitalocean');
    }
}
