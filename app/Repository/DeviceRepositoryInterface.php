<?php

namespace App\Repository;

use App\Models\Device;
use App\Models\Event;
use App\Models\Organisation;
use App\Models\Person;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * @see \App\Repository\Eloquent\DeviceRepository
 */
interface DeviceRepositoryInterface
{
    public function all(): Collection;

    public function getBySlug($slug): Builder;

    public function getActive($uuid = null, $all = false): Builder;

    public function getFixedPersonOrganisation(Person $person, $uuid): Builder;

    public function getLastRepairStatus($status, $person, $location): Builder;

    public function getLastNewDevices($uuid, $limit = 5): Builder;

    public function filter(Builder $query, $fixedOnly = false): Builder;

    public function updateContact(Device $device, $data): void;

    public function queryByOrganisationAndStatus(array $organisations, $status = null, $grouped = false): Builder;

    public function queryByEventAndStatus(Event $event, $status = null, $grouped = false): Builder;

    public function queryByStatus($status = null, $grouped = false): Builder;

    public function returnCount(Builder $query): int;

    public function getByDeviceType(Builder $query, $locale);

    public function getEcoImpact(array $organisation = [], Event $event = null): array;

    public function countRepairersByEvent(Event $event): Builder;
}
