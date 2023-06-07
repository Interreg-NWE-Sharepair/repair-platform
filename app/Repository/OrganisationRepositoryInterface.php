<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @see \App\Repository\Eloquent\OrganisationRepository
 */
interface OrganisationRepositoryInterface
{
    public function all(): Collection;

    public function findByCode($uuid): Builder;

    public function findBySlug($slug, $locale): Builder;

    public function findOrFail($id): ?Model;

    public function getAvailable($locale = null, $limit = null, $ordered = false);
}
