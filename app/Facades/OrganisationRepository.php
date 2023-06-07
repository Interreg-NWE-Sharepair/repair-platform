<?php

namespace App\Facades;

use App\Repository\OrganisationRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Collection all()
 * @method static Builder findByCode($uuid)
 * @method static Builder findBySlug($slug, $locale)
 * @method static Builder getAvailable($locale = null, $limit = null, $ordered = false)
 * @see OrganisationRepositoryInterface
 */
class OrganisationRepository extends Facade
{
    protected static function getFacadeAccessor()
    {
        return OrganisationRepositoryInterface::class;
    }
}
