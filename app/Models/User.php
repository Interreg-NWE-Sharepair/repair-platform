<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User
 *
 * @property string $name
 * @property string $email
 * @property string $password
 * @property \Carbon\Carbon $email_verified_at
 * @property string $locale
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verfified_at',
        'locale',
        'ignore_automated_emails',
    ];

    protected $dates = [
        'email_verified_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return bool
     */
    public function canImpersonate(): bool
    {
        if ($this->hasRole('statik')) {
            return true;
        }

        return false;
    }

    public function person()
    {
        return $this->hasOne(Person::class);
    }

    public function employees()
    {
        return $this->hasManyThrough(Employee::class, Person::class);
    }


    public function repairLogs()
    {
        return $this->hasManyThrough(RepairLog::class, Person::class);
    }
}
