<?php

namespace App\Repository\Eloquent;

use App\Models\Event;
use App\Models\Organisation;
use App\Repository\EventRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class EventRepository extends BaseRepository implements EventRepositoryInterface
{
    /**
     * Event constructor.
     *
     * @param Event $model
     */
    public function __construct(Event $model)
    {
        parent::__construct($model);
    }

    public function getFuture($uuid): Builder
    {
        return $this->model::query()->with([
            'organisation',
            'people',
        ])->selectRaw('events.*')->future()->organisation($uuid)->orderBy('date_start');
    }

    public function getPast($uuid): Builder
    {
        return $this->model::query()->selectRaw('events.*')->past()->organisation($uuid)->orderBy('date_start', 'desc');
    }

    public function getFuturePlusPastDays($uuid, $days): Builder
    {
        return $this->model::query()->selectRaw('events.*')->futurePlusPastDays($days)->organisation($uuid)
                           ->orderBy('date_start');
    }

    public function getAllForOrganisation($uuid): Builder
    {
        return $this->model::query()->selectRaw('events.*')->with([
            'organisation',
            'people',
        ])->organisation($uuid);
    }

    public function find($eventId): Builder
    {
        return $this->model::query()->where('id', $eventId);
    }

    public function getBySlug($slug): Builder
    {
        return $this->model::query()->where('slug', $slug);
    }


    public function countByOrganisation(array $organisations): int
    {
        return $this->model::query()->past()->whereHas('organisation', function($q) use ($organisations) {
            $q->whereIn('uuid', $organisations);
        })->count();
    }
}
