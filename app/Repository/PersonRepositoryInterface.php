<?php

namespace App\Repository;

use App\Models\Event;
use App\Models\Person;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * @see \App\Repository\Eloquent\PersonRepository
 */
interface PersonRepositoryInterface
{
    public function getByUser($user): Builder;

    public function getById($id);

    public function search(Builder $query, Request $request);

    public function attendsEvent(Person $person, Event $event);

    public function update(Person $person, array $data);
}
