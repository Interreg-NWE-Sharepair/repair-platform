<?php

namespace App\Facades;

use App\Repository\LocationRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Builder all();
 * @method static Builder findByCode($locationCode)
 * @method static Model|null findOrFail($id)
 * @method static getAvailableLocation($locale, $limit = null)
 * @method static getAvailableOrganisations($locale, $limit = null)
 *
 * @see LocationRepositoryInterface
 */
class LocationRepository extends Facade
{
    protected static function getFacadeAccessor()
    {
        return LocationRepositoryInterface::class;
    }
}
