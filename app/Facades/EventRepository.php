<?php

namespace App\Facades;

use App\Models\Event;
use App\Models\Organisation;
use App\Repository\EventRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Builder getFuture($uuid)
 * @method static Builder getPast($uuid)
 * @method static Builder getFuturePlusPastDays($uuid, $days)
 * @method static Builder getAllForOrganisation($uuid)
 * @method static Builder find($eventId)
 * @method static Builder getBySlug($slug)
 * @method static int countByOrganisation(array $organisations)
 *
 * @see EventRepositoryInterface
 */
class EventRepository extends Facade
{
    protected static function getFacadeAccessor()
    {
        return EventRepositoryInterface::class;
    }
}
