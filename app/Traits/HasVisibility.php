<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasVisibility
{
    /**
     * This method is called upon instantiation of the Eloquent Model.
     * It adds the is_visible fields to the "$fillable" array of the model.
     * And adds it as boolean cast
     *
     * @return void
     */
    public function initializeHasVisibility()
    {
        $this->fillable[] = 'is_visible';
        //$this->casts['is_visible'] = 'boolean';
    }

    public function scopeIsVisible(Builder $query)
    {
        return $query->where('is_visible', 1);
    }

    public function scopeNotVisible(Builder $query)
    {
        return $query->where('is_visible', 0);
    }
}
