<?php

namespace App\Facades;

use App\Models\Event;
use App\Models\Organisation;
use App\Models\Person;
use App\Repository\DeviceRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Collection all()
 * @method static Builder getBySlug($slug)
 * @method static Builder getActive($uuid = null, $all = false)
 * @method static Builder getFixedPersonOrganisation(Person $person, $uuid)
 * @method static Builder getLastRepairStatus($statuses, $person, $location)
 * @method static Builder getLastNewDevices($uuid, $limit = 5)
 * @method static Builder filter(Builder $query, $fixedOnly = false)
 * @method static void updateContact($device, $data)
 * @method static Builder queryByOrganisationAndStatus(array $organisations, $status = null, $grouped = false)
 * @method static Builder queryByEventAndStatus(Event $event, $status = null, $grouped = false)
 * @method static Builder queryByStatus($status = null, $grouped = false)
 * @method static int returnCount(Builder $query)
 * @method static getByDeviceType(Builder $query, $locale)
 * @method static array getEcoImpact(array $organisation = [], Event $event = null)
 * @method static Builder countRepairersByEvent(Event $event)
 *
 * @see DeviceRepositoryInterface
 */
class DeviceRepository extends Facade
{
    protected static function getFacadeAccessor()
    {
        return DeviceRepositoryInterface::class;
    }
}
