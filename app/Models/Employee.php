<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

/**
 * @mixin IdeHelperEmployee
 */
class Employee extends Model
{
    use SoftDeletes;
    use HasRoles;

    protected $guard_name = 'web';

    //TODO add employee types
    const TYPE_ADMIN = 'admin';

    const TYPE_REPAIRER = 'repairer';

    const TYPES = [
        self::TYPE_ADMIN,
        self::TYPE_REPAIRER,
    ];

    protected $with = [
        'person',
        'contactDetails',
        'organisation',
    ];

    protected $appends = [
        'show_employee_info',
        'roles_as_string',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    public function contactDetails()
    {
        return $this->morphMany(ContactDetail::class, 'contactable');
    }

    public function getShowEmployeeInfoAttribute()
    {
        return optional($this->organisation)->show_employee_info;
    }

    public function getFullNameAttribute()
    {
        return $this->person->first_name . ' ' . ucfirst(substr($this->person->last_name, 0, 1)) . '.';
    }

    public function getRolesAsStringAttribute()
    {
        $roleNames = $this->getRoleNames()->toArray();
        foreach ($roleNames as $index => $roleName) {
            $roleNames[$index] = trans("messages.role_$roleName");
        }

        return implode(', ', $roleNames);
    }

    public function scopeUser(Builder $query, $user)
    {
        $query->whereHas('person', function ($q) use ($user) {
            $q->whereHas('user', function ($q) use ($user) {
                $q->where('id', $user->id);
            });
        });
    }

    public function scopeOrganisation(Builder $query, $organisation)
    {
        $query->whereHas('organisation', function ($q) use ($organisation) {
            $q->where('id', $organisation->id);
        });
    }

    public function scopeActive($query)
    {
        return $query->whereHas('person', function ($query) {
            $query->whereHas('user', function ($q) {
                $q->whereNotNull('email_verified_at');
            });
        });
    }

    public function scopeType($query, $type)
    {
        return $query->where('employee_type', $type);
    }
}
