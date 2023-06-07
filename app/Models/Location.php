<?php

namespace App\Models;

use App\Traits\HasQueryParameters;
use App\Traits\HasTranslations;
use App\Traits\HasUuid;
use App\Traits\HasVisibility;
use Carbon\Carbon;
use App\Observers\LocationObserver;
use App\Traits\CanUseRestartersData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Malhal\Geographical\Geographical;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Znck\Eloquent\Traits\BelongsToThrough;

/**
 * @mixin IdeHelperLocation
 */
class Location extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasTranslations;
    use HasTranslatableSlug;
    use HasUuid;
    use SoftDeletes;
    use HasVisibility;
    use Geographical;
    use BelongsToThrough;
    use HasQueryParameters;

    protected $fillable = [
        'uuid',
        'name',
        'description',
        'slug',
        'latitude',
        'longitude',
        'street',
        'number',
        'bus',
        'postal_code',
        'city',
        'country',
        'country_code',
    ];

    public $translatable = [
        'name',
        'description',
        'slug',
    ];

    protected $hidden = ['id'];

    protected $appends = [
        'image_url',
        'telephone',
        'email',
        'website',
        'facebook',
        'instagram',
        'google',
    ];

    protected $with = ['organisation'];

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    public function contactDetails()
    {
        return $this->morphMany(ContactDetail::class, 'contactable');
    }

    public function organisationType()
    {
        return $this->belongsToThrough(OrganisationType::class, Organisation::class);
    }

    public function scopeAvailable(Builder $query)
    {
        return $query->whereHas('organisation', function ($q) {
            $q->where('is_rc_active', 1);
        });
    }

    public function scopeVisible($query)
    {
        return $query->whereHas('organisation', function ($q) {
            $q->where('is_visible', 1);
        });
    }

    public function scopeVirtual($query, $virtual = 1)
    {
        return $query->whereHas('organisation', function ($q) use ($virtual) {
            $q->where('is_virtual', $virtual);
        });
    }

    public function scopeVirtualLast(Builder $query)
    {
        return $query->orderBy('organisations.is_virtual');
    }

    public function scopeHeadquarterFirst(Builder $query)
    {
        return $query->orderByDesc('is_headquarters');
    }

    public function scopeSearch(Builder $query)
    {
        $request = request();

        $organisationTypes = parseQueryArray($request->input('organisation_types'));
        $query->organisationTypes($organisationTypes);

        /**
         * @deprecated
         * LEAVE THIS IN FOR NOW !
         * <still supported by v0 off the mapping api>
         */
        $productCategories = parseQueryArray($request->input('product_categories'));
        $query->deviceTypes($productCategories);
        /**
         * @ end deprecation
         */

        $deviceTypes = parseQueryArray($request->input('device_types'));
        $query->deviceTypes($deviceTypes);

        $bbox = parseQueryArray($request->input('bbox'));
        $query->bbox($bbox);

        return $query;
    }

    public function scopeOrganisationTypes(Builder $query, $organisationTypes = [])
    {
        if ($organisationTypes) {
            $query->whereHas('organisationType', function (Builder $query) use ($organisationTypes) {
                $query->whereIn('organisation_types.code', $organisationTypes)
                      ->orWhereIn('organisation_types.uuid', $organisationTypes);
            });
        }

        return $query;
    }

    public function scopeDeviceTypes(Builder $query, $deviceTypeCodes = [])
    {
        if ($deviceTypeCodes) {
            $query->whereHas('organisation.deviceTypes', function (Builder $query) use ($deviceTypeCodes) {
                $query->whereIn('device_types.code', $deviceTypeCodes)
                      ->orWhereIn('device_types.uuid', $deviceTypeCodes);
            });
        }

        return $query;
    }

    public function scopeBbox(Builder $query, $bbox = [])
    {
        if ($bbox) {
            [
                $minLat,
                $minLong,
                $maxLat,
                $maxLong,
            ] = $bbox;

            $query->whereBetween('latitude', [
                $minLat,
                $maxLat,
            ]);
            $query->whereBetween('longitude', [
                $minLong,
                $maxLong,
            ]);
        }

        return $query;
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile()->withResponsiveImages()->useDisk('digitalocean');

        $this->addMediaCollection('images')->withResponsiveImages()->useDisk('digitalocean');
    }

    public function getLogo()
    {
        return optional($this->getFirstMedia('logo'))->getTemporaryUrl(Carbon::now()->addMinutes(5));
    }

    public function getImage()
    {
        return optional($this->getFirstMedia('images'))->getTemporaryUrl(Carbon::now()->addMinutes(5));
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->getLogo();
    }

    public function getEmailAttribute(): ?string
    {
        return $this->contactDetails()->where('type', ContactDetail::TYPE_EMAIL)->pluck('value')->first();
    }

    public function getWebsiteAttribute(): ?string
    {
        return $this->contactDetails()->where('type', ContactDetail::TYPE_WEBSITE)->pluck('value')->first();
    }

    public function getTelephoneAttribute(): ?string
    {
        $phone = $this->contactDetails->where('type', ContactDetail::TYPE_PHONE)->pluck('value')->first();
        if (!$phone) {
            return $this->contactDetails->where('type', ContactDetail::TYPE_MOBILE)->pluck('value')->first();
        }

        return $phone;
    }

    public function getFacebookAttribute(): ?string
    {
        return $this->contactDetails()->where('type', ContactDetail::TYPE_FACEBOOK)->pluck('value')->first();
    }

    public function getInstagramAttribute(): ?string
    {
        return $this->contactDetails()->where('type', ContactDetail::TYPE_INSTAGRAM)->pluck('value')->first();
    }

    public function getGoogleAttribute(): ?string
    {
        return $this->contactDetails()->where('type', ContactDetail::TYPE_GOOGLE)->pluck('value')->first();
    }

    public function getLocalesAttribute()
    {
        return $this->organisation->locales;
    }

    public function getAddressAttribute()
    {
        $address = null;

        if ($this->street) {
            $street[] = $this->street;
            if ($this->number) {
                $street[] = $this->number;
            }
            if ($this->bus) {
                $street[] = $this->bus;
            }
            $address[] = implode(' ', $street);
        }

        if ($this->postal_code || $this->city) {
            $address[] = implode(' ', array_filter([
                $this->postal_code,
                $this->city,
            ]));
        }

        if ($this->country) {
            $address[] = $this->country;
        }

        return implode(', ', $address);
    }

    public function organisationHasMoreLocations(): bool
    {
        return $this->organisation->locations->count() > 1;
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('logo')->width(150)->height(150)->fit(Manipulations::FIT_CROP, 150, 150)->sharpen(8)
             ->keepOriginalImageFormat()->nonOptimized();

        $this->addMediaConversion('images')->width(150)->height(150)->fit(Manipulations::FIT_CROP, 150, 150)->sharpen(8)
             ->keepOriginalImageFormat()->nonOptimized();
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
            $query->where($field ?? $this->getRouteKeyName(), $value);
            foreach (config('app.supported_locales') as $locale) {
                $query->orWhere('slug->' . $locale, $value);
            }
        })->first();
    }

    public function getUsedLocales()
    {
        return array_keys($this->getTranslations('name'));
    }
}
