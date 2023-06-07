<?php

namespace App\Repository;

use App\Models\Event;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Builder;

/**
 * @see \App\Repository\Eloquent\EventRepository
 */
interface EventRepositoryInterface
{
    public function getFuture($uuid): Builder;

    public function getPast($uuid): Builder;

    public function getFuturePlusPastDays($uuid, $days): Builder;

    public function getAllForOrganisation($uuid): Builder;

    public function find($eventId): Builder;

    public function getBySlug($slug): Builder;

    public function countByOrganisation(array $organisations): int;

}
