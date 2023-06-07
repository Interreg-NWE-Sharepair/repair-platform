<?php

namespace App\Models;

use App\Traits\CanUseRestartersData;
use App\Facades\EmployeeOrganisationRepository;
use App\Facades\PersonRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

/**
 * Class Event
 *
 * @mixin IdeHelperEvent
 */
class Event extends Model
{
    use HasSlug;
    use HasTranslations;
    use CanUseRestartersData;

    public $translatable = [
        'name',
        'description',
    ];

    protected $fillable = [
        'name',
        'description',
        'address',
        'date_start',
        'time_start',
        'time_stop',
        'is_online',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'date_start',
    ];

    protected $appends = [
        'locale_name',
        'locale_description',
        'starts_at',
        'ends_at',
        'date_formatted',
        'registered_devices',
        'attending_repairers',
        'is_attending',
        'is_organizer',
        'is_future',
    ];

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class, 'event_id', 'id');
    }

    public function people()
    {
        return $this->belongsToMany(Person::class);
    }

    public function getLocaleNameAttribute(): string
    {
        $name = $this->name;
        if (!$name) {
            //Get the last available locale that is filled in (in this case English)
            $nameTranslations = collect($this->getTranslations('name'));
            $name = $nameTranslations->last();
        }

        return $name;
    }

    public function getLocaleDescriptionAttribute(): string
    {
        $description = $this->description;
        if (!$description) {
            //Get the last available locale that is filled in (in this case English)
            $descriptionTranslations = collect($this->getTranslations('description'));
            $description = $descriptionTranslations->last();
        }

        return $description ?? '';
    }

    public function getDateFormattedAttribute(): string
    {
        return $this->date_start->format('d/m/Y');
    }

    public function getStartsAtAttribute(): string
    {
        $timeStart = Carbon::createFromTimeString($this->time_start);

        return $this->date_start->isoFormat('DD MMMM YYYY') . ', ' . $timeStart->isoFormat('HH:mm');
    }

    public function getEndsAtAttribute(): string
    {
        $timeStop = Carbon::createFromTimeString($this->time_stop);

        $timeStop = ' - ' . $timeStop->isoFormat('HH:mm');
        if ($this->timezone) {
            $timeStop .= ' (' . $this->timezone . ')';
        }

        return $timeStop;
    }

    public function getRegisteredDevicesAttribute(): int
    {
        return $this->devices()->count('id');
    }

    public function getAttendingRepairersAttribute(): string
    {
        $people = $this->people;
        if ($people && !$people->isEmpty()) {
            $text = '<ul class="list-disc">';
            foreach ($people as $person) {
                /** @var \App\Models\Person $repairer */
                $text .= '<li>' . $person->first_name . ' ' . $person->last_name . '</li>';
            }
            $text .= '</ul>';

            return $text;
        }

        return trans('messages.event_repairers_none');
    }

    /**
     * Check if logged in person has already joined the event
     */
    public function getIsAttendingAttribute(): bool
    {
        if (!auth()->user()) {
            return false;
        }

        $person = PersonRepository::getByUser(auth()->user())->first();
        if ($person) {
            return $this->people()->where('person_id', $person->id)->exists();
        }

        return false;
    }

    public function getIsOrganizerAttribute(): bool
    {
        return auth()->user() && EmployeeOrganisationRepository::isEventOrganizerOrganisation(auth()->user(), $this->organisation);
    }

    public function getIsFutureAttribute(): bool
    {
        $timeStop = Carbon::createFromTimeString($this->time_stop);

        return $this->date_start->setTimeFrom($timeStop)->isFuture();
    }

    /**
     * Returns the start moment of this event based on the data + optional time + timezone
     *
     * @return \Carbon\Carbon
     */
    public function getDateTimeStart(): Carbon
    {
        $dateTimeStart = $this->date_start;
        if ($this->time_start) {
            $dateTimeStart->setTimeFromTimeString($this->time_start);
        }
        if ($this->timezone) {
            $dateTimeStart->setTimezone($this->timezone);
        }

        return $dateTimeStart;
    }

    public function getDropdownTitle()
    {
        $timeStop = Carbon::createFromTimeString($this->time_stop);
        $title = $this->locale_name . ' - ' . $this->starts_at . ' - ' . optional($timeStop)->isoFormat('HH:mm');

        if ($this->street || $this->city) {
            $title .= ' -  ' . $this->street . ' ' . $this->number . ', ' . $this->postal_code . ' ' . $this->city;
        }

        $count = $this->devices->count();
        if ($this->max_devices) {
            $title .= ' - (' . trans('messages.registrations') . ': ' . $count . '/' . $this->max_devices . ')';
        }

        return $title;
    }

    public function hasMaxAmountRegistration()
    {
        return $this->max_devices && $this->devices->count() >= $this->max_devices;
    }

    public function scopeLocale(Builder $query, $locale)
    {
        return $query->where("name->$locale", 'not like', 'null');
    }

    public function scopeFuture(Builder $query)
    {
        return $query->selectRaw('ADDTIME(CONVERT(date_start,datetime),time_stop) as dateTimeEnd')
                     ->having('dateTimeEnd', '>=', Carbon::now());
        //->whereDate('dateTimeEnd', '>=', Carbon::now());
    }

    public function scopePast(Builder $query)
    {
        return $query->selectRaw('ADDTIME(CONVERT(date_start,datetime),time_stop) as dateTimeEnd')
                     ->having('dateTimeEnd', '<', Carbon::now());
        //->whereDate('dateTimeEnd', '<', Carbon::now());
    }

    public function scopeFuturePlusPastDays(Builder $query, $days = 2)
    {

        $carbon = Carbon::now()->subDays($days);

        return $query->selectRaw('ADDTIME(CONVERT(date_start,datetime),time_stop) as dateTimeEnd')
                     ->having('dateTimeEnd', '>=', $carbon);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $locationId
     * @return \Illuminate\Database\Eloquent\Builder
     * @deprecated
     */
    /*public function scopeLocation(Builder $query, $locationId)
    {
        return $query->where('location_id', $locationId);
    }*/

    public function scopeOrganisation(Builder $query, $uuid)
    {
        return $query->whereHas('organisation', function ($q) use ($uuid) {
            $q->where('uuid', $uuid);
        });
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom([
            'name',
            'date_start',
        ])->saveSlugsTo('slug');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
