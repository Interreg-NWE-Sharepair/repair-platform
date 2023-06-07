<?php

namespace App\Repository\Eloquent;

use App\Models\Organisation;
use App\Repository\OrganisationRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class OrganisationRepository extends BaseRepository implements OrganisationRepositoryInterface
{
    public function __construct(Organisation $model)
    {
        parent::__construct($model);
    }

    public function all(): Collection
    {
        return $this->model::query()->visible()->get();
    }

    public function findByCode($uuid): Builder
    {
        return $this->model::query()->where('uuid', $uuid);
    }

    public function findBySlug($slug, $locale): Builder
    {
        return $this->model::query()->findByLocalizedSlug($slug);
    }

    public function getAvailable($locale = null, $limit = null, $ordered = false): Builder
    {
        $query = $this->model::visible()->virtualLast()->available();
        if ($locale) {
            $query->where("name->$locale", 'NOT LIKE', 'null');
        }
        if ($limit) {
            $query->limit($limit);
        }

        if (!$ordered) {
            $query->inRandomOrder();
        } else {
            if (!$locale) {
                $locale = 'nl';
            }
            $query->orderBy("name->$locale");
        }

        return $query;
    }
}
