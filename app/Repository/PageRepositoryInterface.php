<?php

namespace App\Repository;

use App\Models\Page;
use Illuminate\Database\Eloquent\Builder;

/**
 * @see \App\Repository\Eloquent\PageRepository
 */
interface PageRepositoryInterface
{
    public function getBySlug($slug, $locale): Builder;

    public function getRecyclePage(): Page;
}
