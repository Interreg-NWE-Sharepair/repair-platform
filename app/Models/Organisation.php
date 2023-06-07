<?php

namespace App\Models;

use App\Traits\CanUseRestartersData;
use App\Traits\HasTranslations;
use App\Traits\HasUuid;
use App\Traits\HasVisibility;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @mixin IdeHelperOrganisation
 */
class Organisation extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasTranslations;
    use HasTranslatableSlug;
    use HasUuid;
    use SoftDeletes;
    use HasVisibility;
    use CanUseRestartersData;

    protected $fillable = [
        'uuid',
        'name',
        'description',
        'product_description',
        'has_warranty',
        'warranty_description',
        'slug',
        'responsible_group',
        'is_virtual',
    ];

    public $translatable = [
        'name',
        'description',
        'slug',
        'product_description',
        'warranty_description',
    ];

    protected $hidden = ['id'];

    protected $casts = [
        'has_warranty' => 'boolean',
        'is_rc_active' => 'boolean',
        'is_virtual' => 'boolean',
    ];

    protected $appends = [
        'description_short',
        'image',
        'website',
        'email',
        'telephone',
        'facebook',
        'instagram',
        'google',
    ];

    protected $with = [
        'media',
        'contactDetails',
        'organisationType',
    ];

    public function getDescriptionShortAttribute()
    {
        return Str::limit(strip_tags($this->description));
    }

    public function getImageAttribute()
    {
        return $this->getLogo();
    }

    public function getWebsiteAttribute()
    {
        return $this->contactDetails->where('type', ContactDetail::TYPE_WEBSITE)->pluck('value')->first();
    }

    public function getEmailAttribute()
    {
        return $this->contactDetails->where('type', ContactDetail::TYPE_EMAIL)->pluck('value')->first();
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

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function location()
    {
        return $this->hasOne(Location::class, 'organisation_id', 'id');
    }

    public function deviceTypes()
    {
        return $this->belongsToMany(DeviceType::class)->withTimestamps();
    }

    public function organisationType()
    {
        return $this->belongsTo(OrganisationType::class);
    }

    public function contactDetails()
    {
        return $this->morphMany(ContactDetail::class, 'contactable');
    }

    public function organisationLocales()
    {
        return $this->hasMany(OrganisationLocale::class);
    }

    public function activitySectors()
    {
        return $this->belongsToMany(ActivitySector::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function persons()
    {
        return $this->belongsToMany(Person::class, 'employees');
    }

    public function eventAdmins()
    {
        return $this->employees()->role('event-organizer');
    }

    public function entityAdmins()
    {
        return $this->employees()->role('entity-admin');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile()->withResponsiveImages()->useDisk('digitalocean');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        // TODO: Implement registerMediaConversions() method.
    }

    public function getLogo()
    {
        return optional($this->getFirstMedia('logo'))->getTemporaryUrl(Carbon::now()->addMinutes(5));
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', 1);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_rc_active', 1);
    }

    public function scopeVirtual($query, $virtual = 1)
    {
        return $query->where('is_virtual', $virtual);
    }

    public function scopeVirtualLast(Builder $query)
    {
        return $query->orderBy('is_virtual');
    }

    public function scopeOrganisationType(Builder $query, $type)
    {
        return $query->whereHas('organisationType', function (Builder $q) use ($type) {
            $q->where('code', $type);
        });
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug')->allowDuplicateSlugs();
    }

    public function scopeFindByLocalizedSlug($query, $slug)
    {
        $slugField = $this->getSlugOptions()->slugField;

        return $query->where(function ($query) use ($slug, $slugField) {
            $locales = config('app.supported_locales');
            foreach ($locales as $locale) {
                $query->orWhere($slugField . '->' . $locale, urldecode($slug));
            }
        });
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where(function ($query) use ($value, $field) {
            $query->where($field ?? $this->getRouteKeyName(), $value);
            foreach (config('app.supported_locales') as $locale) {
                $query->orWhere('slug->' . $locale, $value);
            }
        })->first();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getLocalesAttribute()
    {
        $locales = $this->organisationLocales;

        if ($locales) {
            return $locales->pluck('locale');
        }

        return collect([]);
    }

    public function generateLocales($fallbackLocales = [])
    {
        $locales = array_keys($this->getTranslations('description'));
        $organisationLocales = [];

        foreach ($locales as $locale) {
            $organisationLocales[$locale] = $locale;
        }

        if (empty($organisationLocales)) {
            foreach ($fallbackLocales as $locationLocale) {
                $organisationLocales[$locationLocale] = $locationLocale;
            }
        }

        $this->addOrganisationLocales($organisationLocales);
    }

    public function addOrganisationLocales(array $organisationLocales)
    {
        $newOrganisationLocales = [];

        $this->load('organisationLocales');
        $currentOrganisationLocales = $this->locales;

        foreach ($organisationLocales as $locale) {
            if (!$currentOrganisationLocales->contains($locale)) {
                $newOrganisationLocales[$locale] = new OrganisationLocale(['locale' => $locale]);
            }
        }

        $this->organisationLocales()->saveMany($newOrganisationLocales);
    }
}
