<?php

namespace App\Facades;

use App\Models\Organisation;
use App\Repository\EmployeeRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Builder getByPerson($person)
 * @method static Builder getById($id)
 * @method static Builder getByUser($user)
 * @method static getByOrganisation(Organisation $organisation)
 * @method static int countByOrganisation(Organisation $organisation = null)
 * @method static search(Builder $query, Request $request)
 * @method static createRepairer(array $data, Organisation $organisation, $locale = 'nl')
 *
 * @see EmployeeRepositoryInterface
 */
class EmployeeRepository extends Facade
{
    protected static function getFacadeAccessor()
    {
        return EmployeeRepositoryInterface::class;
    }
}
