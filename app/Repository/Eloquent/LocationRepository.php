<?php

namespace App\Repository\Eloquent;

use App\Models\Location;
use App\Repository\LocationRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class LocationRepository extends BaseRepository implements LocationRepositoryInterface
{
    /**
     * LocationOld Repository constructor.
     *
     * @param Location $model
     */
    public function __construct(Location $model)
    {
        parent::__construct($model);
    }

    public function all(): Collection
    {
        return $this->model::visible()->get();
    }

    public function findByCode($locationCode): Builder
    {
        return $this->model::query()->where('code', $locationCode);
    }

    public function getAvailableOrganisations($locale, $limit = null)
    {
        $query = $this->model::query()->selectRaw('locations.*, organisations.is_virtual, MAX(locations.id) as max_location')->with('organisation')
                             ->rightJoin('organisations', 'organisations.id', '=', 'locations.organisation_id')
                             ->available()->visible()->virtualLast()
                             ->where("organisations.name->$locale", 'not like', 'null');
        if ($limit) {
            $query->limit($limit);
        }

        $query->headQuarterFirst();

        return $query->groupBy('locations.id')->groupBy('organisations.id')->virtualLast();
    }

    public function getAvailableLocation($locale, $limit = null)
    {
        $locations = $this->model::query()->visible()->available()->VirtualLast()
                                 ->where("name->$locale", 'not like', 'null')->inRandomOrder();
        if ($limit) {
            $locations->limit($limit);
        }

        return $locations->get();
    }
}
