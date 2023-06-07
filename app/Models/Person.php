<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperPerson
 */
class Person extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'location',
        'specialization',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'telephone',
        'full_name',
    ];

    protected $with = ['contactDetails'];

    protected static function boot() {
        parent::boot();

        static::deleting(function(self $person) {
            $person->employees()->delete();
        });
    }

    public function getTelephoneAttribute()
    {
        return $this->contactDetails->where('type', ContactDetail::TYPE_PHONE)->pluck('value')->first();
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . ucfirst(substr($this->last_name, 0, 1)) . '.';
    }

    public function organisations()
    {
        return $this->belongsToMany(Organisation::class, 'employees');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class)->withTimestamps();
    }

    public function repairLogs()
    {
        return $this->hasMany(RepairLog::class);
    }

    public function repairLogNotes()
    {
        return $this->hasMany(RepairLogNote::class);
    }

    public function contactDetails()
    {
        return $this->morphMany(ContactDetail::class, 'contactable');
    }

    /*public function telephone() {
        return $this->morphOne(ContactDetail::class, 'contactable')->where('type', ContactDetail::TYPE_PHONE)->latestOfMany();
    }*/

    public function getAttendingEvents($futureEvents): array
    {
        $attendingEvents = [];
        foreach ($futureEvents as $futureEvent) {
            $attendingEvents[$futureEvent->id] = false;
        }
        $events = $this->events()->pluck('events.id')->all();
        if ($events) {
            foreach ($events as $event) {
                if (isset($attendingEvents[$event])) {
                    $attendingEvents[$event] = true;
                }
            }
        }

        return $attendingEvents;
    }
}
