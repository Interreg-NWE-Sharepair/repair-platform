<?php

namespace App\Repository;

use App\Models\Organisation;
use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * @see \App\Repository\Eloquent\EmployeeRepository
 */
interface EmployeeRepositoryInterface
{
    public function getByPerson(Person $person): Builder;

    public function getById($id);

    public function getByUser(User $user): Builder;

    public function getByOrganisation(Organisation $organisation);

    public function search(Builder $query, Request $request);

    public function countByOrganisation(Organisation $organisation = null): int;

    public function createRepairer(array $data, Organisation $organisation, $locale = 'nl');
}
