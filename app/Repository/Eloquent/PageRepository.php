<?php

namespace App\Repository\Eloquent;

use App\Models\Page;
use App\Repository\PageRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class PageRepository extends BaseRepository implements PageRepositoryInterface
{
    /**
     * PageRepository constructor.
     *
     * @param \App\Models\Page $model
     */
    public function __construct(Page $model)
    {
        parent::__construct($model);
    }

    public function getRecyclePage(): Page
    {
        // Hard coded type in Database (not selectable by user)
        return $this->model::query()->type('recycle')->first();
    }

    public function getBySlug($slug, $locale): Builder
    {
        return $this->model::query()->locale($locale, $slug);
    }
}
