<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin IdeHelperEventPeople
 */
class EventPeople extends Pivot
{
    protected $table = 'event_person';

    public $incrementing = true;

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function scopeHasEvent(Builder $query, Event $event)
    {
        $query->whereHas('event', function ($q) use ($event) {
            $q->where('id', $event->id);
        });
    }

    public function scopeHasPerson(Builder $query, Person $person)
    {
        $query->whereHas('person', function ($q) use ($person) {
            $q->where('id', $person->id);
        });
    }
}
