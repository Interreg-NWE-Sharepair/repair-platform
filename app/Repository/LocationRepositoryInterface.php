<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @see \App\Repository\Eloquent\LocationRepository
 */
interface LocationRepositoryInterface
{
    public function all(): Collection;

    public function findByCode($locationCode): Builder;

    public function findOrFail($id): ?Model;

    public function getAvailableOrganisations($locale, $limit = null);

    public function getAvailableLocation($locale, $limit = null);
}
