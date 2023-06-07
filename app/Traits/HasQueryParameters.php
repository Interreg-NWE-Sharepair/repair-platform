<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasQueryParameters
{
    use HasOptionalLimit, HasOptionalPaginate;

    public function scopeGetByQueryParameters(Builder $query)
    {
        return $query->optionalLimit()->optionalPaginate();
    }
}
