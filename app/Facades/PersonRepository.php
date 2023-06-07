<?php

namespace App\Facades;

use App\Models\Event;
use App\Models\Person;
use App\Repository\PersonRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Builder getByUser($user)
 * @method static Builder getById($id)
 * @method static search(Builder $query, Request $request)
 * @method static attendsEvent(Person $person, Event $event)
 * @method static update(Person $person, array $data)
 *
 * @see PersonRepositoryInterface
 */
class PersonRepository extends Facade
{
    protected static function getFacadeAccessor()
    {
        return PersonRepositoryInterface::class;
    }
}
